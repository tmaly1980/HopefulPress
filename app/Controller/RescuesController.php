<?
class RescuesController extends AppController
{
	var $components  = array('Newsletter.Mailchimp');

	var $uses = array('Rescue','NewsPost','Event','PhotoAlbum','AboutPageBio','Contact');

	var $globalActions = array('index','search','search_bar','rescuer_add','rescuer_edit'); # What doesn't require the rescue to be specified.

	function beforeFilter()
	{
		parent::beforeFilter();
		if(empty($this->rescue) && !in_array($this->request->params['action'],$this->globalActions))
		{
			error_log("BAD RESCUE, ACT={$this->request->params['action']}");
			$this->badRescue();
		}

	}

	function user_select($hostname=null)
	{
		if(!empty($hostname))
		{
			$redirect = $this->Session->read("process.redirect");
			if(empty($redirect))
			{
				$redirect = array('controller'=>'rescues','action'=>'view');
			}
			$redirect['rescue'] = $hostname;

			$this->Session->delete("process.redirect");

			return $this->redirect($redirect);
		}

		# Havent chosen yet.
	}

	function search_bar()
	{
		parent::search_bar();
		$this->Rescue->autoid = false;
		# Within certain radius? Near you?
		$this->set("rescueCount", $this->Rescue->count());
	}

	function index()
	{
		$this->setAction("search");
	}

	function rescuer_view_plan()# View plan
	{
	}

	function rescuer_plans() # Show list of plans.
	{
	}

	# Might be ajax????

	function rescuer_view_billing() # View existing billing details, if any.
	{
		$stripe_id = $this->rescue("stripe_id");
		$subscription_id = $this->rescue("subscription_id");

		$subscription = null;
		if($stripe_id && $subscription_id)
		{
			$subscription = $this->StripeBilling->subscription($stripe_id,$subscription_id);
			if(is_array($subsciption))
			{
				$this->set("subscription", $subscription);
			} else {
				$subscription = null;
			}
		}
		if(empty($subscription)  || empty($subscription['source']))
		{
			$this->setAction("rescuer_edit_billing");
		}
	}

	# MAY HAVE TO TEST!!!!
	function rescuer_edit_billing()
	{
		$stripe_id = $this->rescue("stripe_id");
		if(!empty($this->request->data))
		{
			# Just updating cards.
			$return = $this->StripeBilling->customer($stripe_id,$this->request->data['StripeBilling']);
			if(is_string($return))
			{
				# JSON
				return $this->Json->error("Could not update billing details: $return");
			}
			$this->rescuer_view_billing();
			return $this->Json->render("rescuer_view_billing");
		}
	}

	function rescuer_upgrade($plan=null)
	{
		# XXX always prompt them the  option to choose the yearly discount...
		# if they have a card  on file, let them confirm OR CHANGE!!!


		if(empty($plan))
		{
			return $this->setError("Unable to change plan, no plan specified",array('rescuer'=>1,'action'=>'edit'));
		}
		if($plan == 'free') { $plan = null; }

		# check for current subscription/card, prompt if needed. Keep AJAX in 'PlanDetails'.
		$stripe_id = $this->rescue("stripe_id");
		$subscription_id = $this->rescue("subscription_id");

		$subscription = null;
		if($stripe_id && $subsciption_id)
		{
			$this->set("subscription", $subscription = $this->StripeBilling->subscription($stripe_id,$subscription_id));
		}

		# auto-process downgrade
		if(empty($plan))
		{
			if(!empty($subscription)) # CANCEL
			{
				if($error  = $this->StripeBilling->cancelSubscription($stripe_id,$subscription_id))
				{
					return $this->setError("Could not cancel existing subscription: $error");
				}
			}
			return $this->setSuccess("Account plan successfully changed", array('action'=>'edit'));
		}

		# We always should ask for yearly discount option.

		# Process once we have payment info...
		if(!empty($this->request->data['StripeBilling'])) # PROCESS
		{
			$return = $this->StripeBilling->hostingSubscription($this->request->data['StripeBilling']);
			if(is_string($return))
			{
				return $this->setError("Could not update account plan: $return");
			} else { # SUCCESS
				$this->request->data['StripeBilling']['disabled'] = null;
				if($existingPlan != $this->request->data['StripeBilling']['plan'])
				{
					$this->request->data['StripeBilling']['upgraded'] = date('Y-m-d H:i:s');
				}
				$this->Rescue->id = $this->rescue_id;
				if($this->Rescue->save($this->request->data['StripeBilling']))
				{
					return $this->setSuccess("Account plan successfully changed", array('action'=>'edit')); 
				} else {
					$this->setError("Could not save billing information: ".$this->Rescue->errorString());
				}
			}
		}

		$this->set("plan", $plan);
	}

	function rescuer_edit() # Signup/edit
	{
		# Prompt for Rescue record.
		if(!empty($this->request->data))
		{
			if($this->Rescue->saveAll($this->request->data)) # Should save user_id to me automatically.
			{
				# Update user account too, link to this rescue...
				#
				if(!$this->user("manager"))
				{
					$this->user("rescue_id", $this->Rescue->id); # Updates session too.
				}
				$rescue = $this->Rescue->read();

				if($goto = $this->Session->read("process.redirect")) { # PREFER, ie newly adding adoptables.
					$goto['rescue'] = $rescue['Rescue']['hostname'];
					$this->Session->delete("process.redirect");
				} else {
					$goto = array('rescuer'=>false,'action'=>'view','rescue'=>$rescue['Rescue']['hostname']); 
				}

				return $this->setSuccess(
					(!empty($this->request->params['rescue']) ? 
						"Rescue listing updated!":"Rescue listing created!")
					,$goto);
			} else {
				return $this->setError("Could not create rescue listing: ".$this->Rescue->errorString());
			}
		}
		if(!empty($this->rescue_id))
		{
			$this->request->data = $this->Rescue->read(null,$this->rescue_id);
		}

		# Specify restriction options...
		$this->set("ageGroups", $this->Adoptable->dropdown("age_groups"));
		$this->set("adultSizes", $this->Adoptable->dropdown("adult_sizes"));
	}

	function view() #$hostname=null) # "home" page
	{
		$hostname = !empty($this->rescuename) ? $this->rescuename : null;

		#if(!empty($this->request->params['rescue']))
		#{
		#	$hostname = $this->request->params['rescue'];
		#}

		if(empty($hostname) || !($rescue = $this->Rescue->findByHostname($hostname)))
		{
			return $this->setError("Rescue not found",array('action'=>'index'));
		}
		# Maybe get other important records...

		$rid = Configure::read("rescue_id");

		# Set updates.
		$updates = array(
			'newsPosts'=>$this->NewsPost->find('recent', array('conditions'=>array('NewsPost.rescue_id'=>$rid))),
			'upcomingEvents'=>$this->Event->find('upcoming', array('conditions'=>array('Event.rescue_id'=>$rid))),
			'photoAlbums'=>$this->PhotoAlbum->find('recent', array('conditions'=>array('PhotoAlbum.rescue_id'=>$rid))),
		);
		$this->set("updates", $updates);

		$this->set("rescue", $rescue);
	}

	function about()
	{
		$this->set("aboutPageBios", $this->AboutPageBio->find('all',array('rescue_id'=>$this->rescuename)));
	}

	function contact()
	{
		$this->set("contacts", $this->Contact->find('all',array('rescue_id'=>$this->rescuename)));
	}

	function search()
	{
		# Possible criteria to filter.
		$cond = array();
		$rescues = $this->Rescue->find('all',array('conditions'=>$cond));

		foreach($rescues as &$rescue) # STATS
		{
			$rid = $rescue['Rescue']['id'];
			$count = $rescue['adoptableCount'] = $this->Adoptable->count(array('rescue_id'=>$rid,'status'=>'Available'));

			# TODO DISTANCE?
		}

		$this->set("rescues", $rescues);
	}

	function widget() # Local, etc. sidebar
	{
		$this->set("rescues", $this->Rescue->find('all'));
	}

	function beforeRender()
	{
		Configure::load("breeds");
		$breeds = Configure::read("Breeds");
		$this->set("breeds", $breeds);
		$species = array();
		# Pluralize species...
		foreach(array_keys($breeds) as $spec)
		{
			$species[$spec] = Inflector::pluralize($spec);
		}

		$this->set("species",$species);

		return parent::beforeRender();
	}

	function style($theme = 'default')
	{
		if(empty($this->rescue)) # No rescue specified,ignore.
		{
			echo "/* No rescue specified */";
			exit(0);
		}

		$design_keys = array('theme','color1'); # So far.

		foreach($design_keys as $k) { $this->set($k,$this->rescue['Rescue'][$k]); }
		foreach($this->request->params['named'] as $k=>$v) { $this->set($k,$v); } # Override (previewer)

		$this->set("theme", $theme); # Let us override from URL, ie if previewed.
		$this->response->type("css");
		$this->layout = false;#'none';
	}

	####################################
	# Mailchimp sign in/out
	function rescuer_mailinglist_login()
	{
		return $this->Mailchimp->login('mailinglist_oauth');
	}

	function rescuer_mailinglist_logout() #  Remove credentials/sign out
	{
		$this->Mailchimp->logout();
		return $this->setSuccess("Signed out of MailChimp", array('action'=>'edit','#'=>'mailinglist'));
	}

	function rescuer_mailinglist_oauth() # Accept credential  response...
	{
		if($error = $this->Mailchimp->oauth())
		{
			$this->setError("Could not sign in to Mail Chimp: $error", array('action'=>'edit','#'=>'mailinglist'));
		}  else {
			return $this->setSuccess("Thanks for signing in to Mail Chimp. A subscription form has been enabled on your homepage and you can send out emails/newsletters at any time.", array('action'=>'edit','#'=>'mailinglist'));
		}
	}

	function subscribe()
	{
		if(!empty($this->request->data['Subscriber']))
		{	
			#WHERE DO THEY GO AFTER  THEY CONFIRM?
			$this->Mailchimp->subscribe($this->request->data['Subscriber']);
			$this->setSuccess("Please check your email and follow the instructions to confirm your subscription.");
		} else {
			$this->setError("OOPS, could not subscribe");
		}
		$this->redirect(array('action'=>'view'));
	}

	function unsubscribe() # Posted via ajax
	{
		if(!empty($this->request->data['Subscriber']['email']))
		{
			$email = $this->request->data['Subscriber']['email'];
			if($this->Mailchimp->unsubscribe($email))
			{
				$this->setSuccess("Please follow the instructions in your email to complete your unsubscription");
			} else { # Never was there.
				$this->setSuccess("You have been unsubscribed from our mailing list");
			}
		} else {
			$this->setWarn("Please provide your email address to process your unsubscription");
		} 
		$this->redirect(array('action'=>'view'));
	}


}
