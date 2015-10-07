<?
#Configure::write("Mailchimp.apiKey", "832a42a9afe2e0499069319bf76f5a64-us11");
#Configure::write("Mailchimp.exceptions", true);
# THIS BELOW IS FOR MY APP
#Configure::write("Mailchimp.client_id", "802231070445");
#Configure::write("Mailchimp.client_secret", "b7fdc4e4efb5ff3df4771f6aa8c46136");

App::uses("NewsletterAppController", "Newsletter.Controller"); 
class SubscribersController extends NewsletterAppController
{
	# XXX TODO switch mailchimp to COMPONENT??? I'm just going to be calling get/post anyway. no strict object relationship

	var $components = array('Newsletter.Mailchimp');

	var $uses  = array('Newsletter.Subscriber');#,'Mailchimp.Mailchimp','Mailchimp.MailchimpSubscriber');

	var $listNames = array('Subscribers');# FOR NOW #,'Adopters','Volunteers','Fosters','Donors');

	function user_login() # Redirect to  proper url..
	{
		return $this->Mailchimp->login();
	}

	function user_logout() #  Remove credentials/sign out
	{
		$this->Mailchimp->logout();
		return $this->setSuccess("Signed out of MailChimp", array('action'=>'index'));
	}

	function user_oauth() # Accept credential  response...
	{
		if($error = $this->Mailchimp->oauth())
		{
			$this->setError("Could not sign in to Mail Chimp: $error", "/user/newsletter/subscribers");
		}  else {
			return $this->setSuccess("Thanks for signing in to Mail Chimp. A subscription form has been enabled on your homepage and you can send out emails/newsletters at any time.", "/user/newsletter/subscribers");

		}
	}

	function user_add($list_id) # Any list.
	{
		if(!empty($this->request->data['Subscriber']))
		{
			if($this->Mailchimp->subscribe($this->request->data['Subscriber'],$list_id))
			{
				return $this->setSuccess("Subscriber added",array('action'=>'index'));
			
			}
		}
		$this->set("list_id",$list_id);
	}

	function contact_details()
	{
		$contact = $this->ContactPage->first();
		if(empty($contact) || empty($contact['ContactPage']['address']) 
			|| empty($contact['ContactPage']['city']) 
			|| empty($contact['ContactPage']['state'])
			|| empty($contact['ContactPage']['zip_code'])
		)
		{
			# XXX Issue with acl's.... cant write  to contact page if a non-admin.
			return $this->setWarn("Please complete your address/contact information prior to using your mailing list, to comply with emailing laws","/admin/contact_pages/edit");
		}
		return $contact['ContactPage'];
	}


	function user_index()
	{
		# ALWAYS make sure they have contact info filled out FIRST!
		$contact = $this->contact_details();
		$credentials = $this->MailchimpCredential->first();
		if(!empty($credentials))
		{
			# Copy access_token
			# GET LIST FROM MAILCHIMP
			$lists = $this->Mailchimp->get("lists");

			# ALWAYS MAKE SURE WE HAVE CORE LISTS
			$listNames = Set::extract($lists['lists'], "{n}.name");


			$missingListNames = array_diff($this->listNames,$listNames);

			# Create initial list.
			if(!empty($missingListNames))
			{
				foreach($missingListNames as $listName)
				{
					# Make 'Subscribers' default list (ie for subscribe form on homepage)
					$this->Mailchimp->create_list($listName,$contact);

				}

				$lists = $this->Mailchimp->get("lists");
			}

			$subscribers = $list_names = array();

			foreach($lists['lists'] as $l)
			{
				$lid = $l['id'];
				$lname = $l['name'];
				#echo "LID=$lid, LNAME=$lname\n";
				$list_names[$lid] = $lname;
				$list_totals[$lid] = $l['stats']['member_count'];
				$subscribers[$lid]  = $this->Mailchimp->members($lid);
			}

			$this->set("lists", $subscribers);
			$this->set("list_names", $list_names);
			$this->set("list_totals", $list_totals);
		}
	}

	function user_edit($id = null)
	{
		if(!empty($this->request->data))
		{
			if($this->Subscriber->save($this->request->data))
			{
				$subscriber = $this->Subscriber->read();
				if(empty($id))  # Created, send opt-in email.
				{
					$this->sendSubscriberEmail($this->Subscriber->id, "Confirm mailing list subscription", "Newsletter.confirm",array('subscriber'=>$subscriber));
				}
				$this->setSuccess("The subscriber has been saved", array('action'=>'index'));
			} else {
				$this->setError("Could not save subscriber. ".$this->Subscriber->errorString());
			}
		} else  if (!empty($id)) { 
			$this->request->data = $this->Subscriber->read(null, $id);
		}
	}

	function confirm($email,$code)
	{
		$subscriber = $this->Subscriber->find('first', array('conditions'=>array('email'=>$email)));
		if(!empty($subscriber))
		{
			if(empty($subscriber['Subscriber']['confirm_code']) && !empty($subscriber['Subscriber']['confirmed']))
			{
				$this->setSuccess("That email is already on our mailing list", "/");
			} else if($subscriber['Subscriber']['confirm_code'] == $code) {
				$this->Subscriber->save(array('Subscriber'=>array('confirm_code'=>null,'confirmed'=>date("Y-m-d H:i:s"))));
				$this->setSuccess("Thanks for confirming your subscription", "/");
			} else {
				$this->setErrror("Sorry, we could not find that email address in our mailing list", "/");
			}
		} else {
			$this->setErrror("Sorry, we could not find that email address in our mailing list", "/");
		}
	}

	function widget() # Widget/action
	{
		if(!empty($this->request->data['Subscriber']))
		{	
			#WHERE DO THEY GO AFTER  THEY CONFIRM?
			$this->Mailchimp->subscribe($this->request->data['Subscriber']);
			return $this->setSuccess("Please check your email and follow the instructions to confirm your subscription.", "/");
		}
		/*
		if(!empty($this->request->data))
		{
			if($this->Subscriber->save($this->request->data))
			{
				$subscriber = $this->Subscriber->read();
				$this->sendSubscriberEmail($this->Subscriber->id, "Confirm mailing list subscription", "Newsletter.confirm",array('subscriber'=>$subscriber));
				$this->setSuccess("Thanks! We've sent you an email to confirm your subscription");
			} else {
				$this->setError("Could not add your email to our mailing list. ".$this->Subscriber->errorString());
			}
		}
		$this->redirect("/");
		*/
	}

	function unsubscribe()
	{
		if(!empty($this->request->data['Subscriber']['email']))
		{
			$email = $this->request->data['Subscriber']['email'];
			if($this->Mailchimp->unsubscribe($email))
			{
				$this->setSuccess("Please follow the instructions in your email to complete your unsubscription", "/");
			} else { # Never was there.
				$this->setSuccess("You have been unsubscribed from our mailing list", "/");
			}

		}

	}

}
