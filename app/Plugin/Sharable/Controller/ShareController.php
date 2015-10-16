<?
class ShareController extends AppController
{
	var $components = array('Core.CoreEmail');
	var $uses = array('SiteShare');

	# TODO route ourselves so we can someday track.

	# XXX site_shares AS OWN TABLE (not a page view NOR a visit)

	function share($via = null)
	{
		error_log("V=$via, RQ=".print_r($this->request->query,true)); # ITS NOT A GET WITH THE EMAIL!
		if(!empty($via) && method_exists($this, $via) && !empty($this->request->query['page_url']))
		{
			error_log("VALID EMAIL");
			$url = !empty($this->request->query['page_url']) ? 
				$this->request->query['page_url'] : null;
			$title = !empty($this->request->query['page_title']) ?
				$this->request->query['page_title'] : null;

			if($via != 'email_message')
			{
				#$this->Tracker->track_share($via);
			} # Email share delay.

			$this->setAction($via, $url, $title); # 
		} else {
			$this->setError("Could not share page, the link appears to be broken");
			if($this->request->is('ajax'))
			{
				$this->Json->redirect($this->referer()); # Worst case.
			} else {
				$this->redirect($this->referer()); # Worst case.
			}
		}
	}

	function facebook()
	{
		$url = !empty($this->request->query['page_url']) ? $this->request->query['page_url'] : null;
		$title = !empty($this->request->query['page_title']) ? $this->request->query['page_title'] : null;
		$this->redirect("http://www.facebook.com/sharer.php?p[url]={$url}&p[title]={$title}");
	}

	function twitter()
	{
		$url = !empty($this->request->query['page_url']) ? $this->request->query['page_url'] : null;
		$title = !empty($this->request->query['page_title']) ? $this->request->query['page_title'] : null;
		$this->redirect("http://twitter.com/home?status={$url}");
	}

	function admin_email_message()
	{
		$this->setAction("email_message");
	}

	function email_message() # Form.
	{
		if(!empty($this->request->data))
		{
			error_log("DATA=".print_r($this->request_data,true));
			# Actual send.
			#$this->Tracker->track_share('email');

			if($this->sendShareEmail($this->request->data))
			{
				$this->Json->script("$.dialogclose(); BootstrapDialog.alert('Your email has been sent.');");
				return $this->Json->render();
			}

		}
		error_log("VIEWING EMIL OOOPIP");
		# Else view form
	}

	function public_url($url)
	{
		$parsed = Router::parse($url);
		$parsed['admin'] = 0;
		$parsed['manager'] = 0;
		$parsed['prefix'] = null;

		if(!empty($parsed['pass']))
                {
                        foreach($parsed['pass'] as $k=>$v)
                        {
                                $parsed[$k] = $v;
                        }
                        unset($parsed['pass']);
                }


		$url = Router::url($parsed);
		return $url;
	}

	function copypaste() # Friendly url copy/paste
	{ # Pass as $_GET['page_url']
		$page_url = $this->request->query['page_url'];
		$public_url = $this->public_url($page_url);

		if(empty($public_url))
		{
			$this->setFlash("There is an internal error creating this link.");
			return;
		}
		$this->set("public_url", $public_url);
	}

	function admin_copypaste() { return $this->setAction("copypaste"); }

	function send()
	{
		if(!empty($this->data['Email']))
		{
			$vars = array();
			$sitename = $current_site['Site']['title'];
			$hostname = !empty($current_site['Site']['domain']) ?
				$current_site['Site']['domain'] :
				$current_site['Site']['hostname']."hopefulpress.com";

			$page_url = $this->data['Email']['page_url'];

			$name = !empty($this->data['Email']['name']) ?
				$this->data['Email']['name'] : $sitename;

			$this->data['Email']['from'] = "$name <noreply@$hostname>";
			$subject = !empty($this->data['Email']['subject']) ?
				$this->data["Email"]['subject'] :
				"Link from $sitename";
			# Passes name, subject, content

			$to = !empty($this->data['Email']['to']) ?
				$this->data['Email']['to'] : null;

			$template = 'share';

			###$this->Email->sendAs = 'text';

			if(empty($to) || empty($page_url) || !$this->HpEmail->sendEmail($to, $subject, $template, $this->data['Email']))
			{
				$this->setFlash("Unable to send email. Please try again.");
			} else {
				$this->action = 'sent';
			}
		}
	}
}
