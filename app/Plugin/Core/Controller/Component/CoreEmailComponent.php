<?
App::uses("HostInfo", "Core.Lib");

class CoreEmailComponent extends Component
{
	var $components = array(
		'Email', 'Session','Auth'
	);

	var $smtpOptions = null; # If set at all, assume 'smtp' delivery.
	var $delivery = 'mail'; # or 'smtp'

	var $defaultFrom = null; # If null, checks app_title
	var $defaultReplyTo = null;
	var $support = 'support'; # If set, send to him instead of ppl in system (so my personal email isn't cluttered w/work)
	var $domain = 'localhost'; # Pick something better!!!
	var $devEmail = null; # Set to email if all email forwarded to (if Configure::read("dev"))
	var $subjectPrefix = null;
	var $defaultCC = null;
	var $layout = 'Core.default'; # under View/Layouts/Emails/html/
	var $managers = 'support'; # Default

	function initialize(Controller $controller)
	{
		parent::initialize($controller);
		$this->controller = $controller;

		if(empty($this->domain)) { $this->domain = HostInfo::domain(); }

		$this->Email->layout = $this->layout;
	}

	function userEmail($to, $subject, $template, $vars = array()) # Assumes User id/objects. If not, then parse beforehand into emails explicitly.
	{ # Can take user objects or user ids, converts to email.
		$emailField = Configure::read("User.emailField");
		if(empty($emailField)) { $emailField = 'email'; }
		$prikey = $this->controller->User->primaryKey;

		# Go thru 'to' list and parse as users
		$tolist = !is_array($to) ? array($to) : $to;
		$toout = array();
		foreach($tolist as $touser)
		{
			if(is_numeric($touser)) # User id.
			{
				$toout[] = $this->controller->User->field($emailField, array($prikey=>$touser));
			} else if (is_array($touser) && !empty($touser['User'][$emailField])) {
				$toout[] = $touser['User'][$emailField];
			} else if (is_array($touser) && !empty($touser[$emailField])) {
				$toout[] = $touser[$emailField];
			} else if (!is_array($touser)) { # Assume email.
				$toout[] = $touser;
			} # Else, bogus
		}

		return $this->send($toout, $subject, $template, $vars);
	}

	# To Tech support
	function supportEmail($subject, $template, $vars = array())
	{
		#$this->Email->layout = 'support';

		$techs = is_array($this->support) ? $this->support : split(",", $this->support);

		error_log("EMAILING SUPPORT=".print_r($techs,true));

		return $this->send($techs, $subject, $template, $vars);
	}

	# XXX distinguish betweeen MY mailing lists and THEIRS
	function subscriberEmail($to, $subject, $template, $vars = array())
	{
		#$this->Email->layout = 'subscriber';

		$emailField = 'email';
		$prikey  = 'id';

		# Go thru 'to' list and parse as users
		$tolist = !is_array($to) ? array($to) : $to;
		$toout = array();
		foreach($tolist as $tosubscriber)
		{
			if(is_numeric($tosubscriber)) # User id.
			{
				$toout[] = $this->controller->Subscriber->field($emailField, array($prikey=>$tosubscriber));
			} else if (is_array($tosubscriber) && !empty($tosubscriber['Subscriber'][$emailField])) {
				$toout[] = $tosubscriber['Subscriber'][$emailField];
			} else if (is_array($tosubscriber) && !empty($tosubscriber[$emailField])) {
				$toout[] = $tosubscriber[$emailField];
			} else if (!is_array($tosubscriber)) { # Assume email.
				$toout[] = $tosubscriber;
			} # Else, bogus
		}
		return $this->send($toout, $subject, $template, $vars);
	}

	function managerEmail($subject, $template, $vars = array())
	{
		#$this->Email->layout = 'support';

		$managers = is_array($this->managers) ? $this->managers : split(",", $this->managers);

		return $this->send($managers, $subject, $template, $vars);
	}

	function set($k,$v)
	{
		$this->controller->set($k,$v);
	}

	function email($to, $subject, $template, $vars = array())
	{
		return $this->send($to, $subject, $template, $vars);
	}

	function send($toEmail, $subject, $template, $vars = array())
	{
		if(empty($this->defaultFrom))
		{
			$this->defaultFrom = Configure::read("site_title") . " <notification>";
		}

		if(empty($vars['default_domain']))
		{
			$vars['default_domain'] = HostInfo::default_domain();
		}

		$this->Email->reset();
		if(empty($vars['from']))
		{
			$this->Email->from = $this->qualify($this->defaultFrom);
			$this->Email->replyTo = $this->qualify(!empty($vars['replyToEmail']) ? $vars['replyToEmail'] : $this->defaultReplyTo);
		} else {
			$this->Email->from = $this->qualify($vars['from']);
			$this->Email->replyTo = $this->qualify($vars['from']);
			# For now, we'll handle emails coming in, if by accident.
			# until someday offer them emails of their own...
		}
		$vars['toEmail'] = $this->qualify($toEmail);

		# Just to keep an eye on things.
		# Either set defaultCC or pass cc=>true (NEVER will cc when cc=>false)
		$defaultCC = (($this->defaultCC && !isset($vars['cc'])) || !empty($vars['cc']));
		if(!Configure::read("dev") && !Configure::read("test") && $defaultCC)
		{
			$this->Email->cc = $this->defaultCC; # Only in production mode, otherwise goes to test/dev emails.
		}

		if(Configure::read("dev")) { $this->subjectPrefix = 'DEV'; }
		if(Configure::read("test")) { $this->subjectPrefix = 'TEST'; }

		# ??? How do we handle per-site host vars??? (pass in AppController::email wrapper!)

		#error_log("DEV=".Configure::read("dev").", DEV_EM=".print_r($this->devEmail,true));

		if(Configure::read("dev") && $this->devEmail)
		{
			$toEmail = $this->qualify($this->devEmail);
		}
		else if(Configure::read("test") && $this->testEmail)
		{
			$toEmail = $this->qualify($this->testEmail);
		}

		#error_log("TO=".print_r($toEmail,true));

		$this->Email->to = $this->qualify($toEmail);
		$this->Email->subject = (!empty($this->subjectPrefix) ? "[{$this->subjectPrefix}] ": "") . $subject;
		# Maybe change to name of org?
		$this->Email->template = $template;
		$this->Email->sendAs = 'html';

		if(!Configure::read("dev") && !empty($this->smtpOptions))
		{
			$this->Email->smtpOptions = $this->smtpOptions;
			$this->delivery = 'smtp';
		}
		$this->Email->delivery = $this->delivery;

		foreach($vars as $k=>$v)
		{
			$this->controller->set($k,$v);
		}

		$rc = $this->Email->send() ? true : false;
		$this->smtpError = $this->Email->smtpError;
		if($this->smtpError)
		{
			$rc = false;
		}
		return $rc;
	}

	function qualify($email)
	{
		$domain = Configure::read("email_domain"); # May vary, ie multiple sites in one app.
		if(empty($domain)) { $domain = $this->domain; }

		$emails = (array)$email;
		$emailsout = array();
		foreach($emails as $email)
		{
			if(!preg_match("/@/", $email))
			{
				if(preg_match("/(.*)<(.*)>(.*)/", $email, $match))
				{
					$email = "\"{$match[1]}\" <{$match[2]}@{$domain}>{$match[3]}";
				} else {
					$email = "$email@{$domain}";
				}
			}
			$emailsout[] = $email;
		}

		return $emailsout;
	}

	function sendList($to, $subject, $template, $vars = array(), $replyTo = null) # Default layout is for peer notification
	# Supports multiple recipients, by id, literal email, array, csv, etc.
	{
		if(!empty($replyTo)) { $vars['replyTo'] = $replyTo; }

		if(!empty($vars['id']) && empty($vars['thing_url']))
		{
			$vars['thing_url'] = Router::url(array('action'=>'view',$vars['id']),true);
			$vars['thing'] = $this->controller->{$this->controller->modelClass}->thing();
		}

		$subject_prefix = isset($vars['subject_prefix']) ? $vars['subject_prefix'] : null;

	#	error_log("EMAIL TO=".print_r($to,true));
	#	error_log("VARS=".print_r($vars,true));
		# Figure out email for recipient. Can be ID, [User] or email.
		$toEmail = null;
		$user = null;
		# If we want more than one user looked up, we should pass list of id's.
		if (is_string($to) && preg_match("/[^@]+@.+([.].+)+/", $to)) {  # One or more emails.

		} else if (is_array($to) && !empty($to['User']['email'])) {  
			$to = $to['User']['email'];

		} else if (is_array($to) && !empty($to['email']) && preg_match("/[^@]+@.+([.].+)+/", $to['email'])) {  
			$to = $to['email'];
		} 

		# 

		if(!is_array($to)) { # Allow for csv....
			$to = split(",", $to); # If just one, split will just turn into an array.
		}

		# Now loop thru recips.
		$recips = array();
		$recip_ids = array();
		foreach($to as $recip)
		{
			#error_log("RECP=$recip, ");
			if(is_numeric($recip)) { $recip_ids[] = $recip; }
			else if(is_array($recip) && !empty($recip['User']['email'])) { $recips[] = $recip['User']['email']; }
			else if(is_array($recip) && !empty($recip['email']) && preg_match("/[^@]+@.+([.].+)+/", $recip['email'])) { $recips[] = $recip['email']; }
			else if (is_array($recip)) { 
				#error_log("ARRAY RECIP=".print_r($recip,true));

			}
			else if(preg_match("/[^@]+@.+([.].+)+/", $recip)) { $recips[] = $recip; }
			# Else, invalid.
		}

	#	error_log("EMAI RECUIP IDS=".print_r($recip_ids,true));


		if(!empty($recip_ids))
		{
			$recip_users = $this->controller->User->find('all',array('conditions'=>array('User.id'=>$recip_ids), 'fields'=>array('email')));
			##$this->User->printLog();
			#error_log("RECIP_USERS=".print_r($recip_users,true));
			foreach($recip_users as $recip_user)
			{
				$recips[] = $recip_user['User']['email'];
			}
		}

		#$toEmail = join(", ", $recips);

		#error_log("SENDING EMAIL TO $toEmail");

		if(!isset($vars['sender']) && !empty($this->Auth))
		{
			$vars['sender'] = $this->Auth->user();
		}

		# Custom message on page. Email.message
		$vars['_message'] = !empty($this->controller->data['Email']['message']) ? $this->controller->data['Email']['message'] : null; 
		# Anything custom in the form.

		# Should specify site data when calling email()

		$emailField = Configure::read("User.emailField");
		if(empty($emailField)) { $emailField = 'email'; }
		$nameField = $this->controller->User->displayField;
		$vars['replyToEmail'] = !empty($vars['sender'][$emailField]) ? "{$vars['sender'][$nameField]} <{$vars['sender'][$emailField]}>" : null;

		if(empty($recips) || empty($recips[0])) { $this->controller->setFlash("Cannot send email, unable to determine email for sending.",'error'); error_log("Couldnt send email, no emails could be found."); return false; }

		$rcs = array();

		foreach($recips as $toEmail) # Send separately... so $user is available.
		{
			$user = $this->controller->User->first(array($emailField=>$toEmail));
			$this->set("user", $user); # Set for this email.

			$rc = $this->send($toEmail, $subject, $template, $vars);

			$this->mailErrors = $this->smtpError;

			$rcs[] = $rc;
			if(!$rc) { error_log("Failed to send email to $toEmail: ".print_r($this->mailErrors,true)); }
			# Someday notify ME
		}
		#error_log("RCS=".print_r($rcs,true).", MIN=".min($rcs));
		# Do something with errors?
		return !empty($rcs) ? min($rcs) : false; # So if any false, returns false
	}


}
?>
