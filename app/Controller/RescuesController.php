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

	function user_edit() # Signup/edit
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
