<?
App::uses("DomainManager","Dns.Vendor");

class DnsController extends DnsAppController
{
	var $components = array('Stripe.StripeBilling');
	var $uses = array();

	var $layout = 'admin';

	var $master_server = "hopefulpress.com";

	function beforeFilter()
	{
		error_log("IN DNS BEFORE_FILTER");
		parent::beforeFilter();

		$this->Domain = new DomainManager();
	}

	function whois($domain)
	{
		error_log("DNS WHOIS");
		$whois = $this->Domain->whois($domain);

		header("Content-Type: text/plain");
		echo "WHOIS=".print_r($whois,true);

		$path = "/regrinfo/domain/nserver";
		echo "EXTRACT=$path\n";

		$nameservers = Set::extract($path, $whois);
		echo "NS2=".print_r(Set::extract("/regrinfo",$whois),true);#print_r($nameservers,true);
		echo "NS3=".print_r(Set::extract("/regrinfo/domain",$whois),true);#print_r($nameservers,true);
		echo "NS4=".print_r(Set::extract($path,$whois),true);#print_r($nameservers,true);
		echo "NS5=".print_r($whois['regrinfo']['domain']['nserver'],true);
		exit(0);



	}

	function dns_complete()
	{
		return HostInfo::domain_ready($this->site("domain"));
	}

	function admin_view($id = null) # Usually ajaxy.
	{
		$this->set("dns_complete", $this->dns_complete());

		error_log("DNS VIEW AJAXY");
		# Double check whether dns setup is complete, then save if possible.
		#$domain = $this->get_site("domain");
		#if($domain) {
			#$completed = $this->_check_domain_complete($domain); # ALWAYS
			#$this->set("dns_complete", $completed);
		#}
	}

	function _check_domain_complete($domain) # If we see further problems/lag on page, we should move this to an ajax call...
	{ # IF NAMESERVER DOESNT MATCH OURS, WE NEED THEM TO DO MORE WORK!
		error_log("CHECK NAMESERVERS $domain....");
		# NS lookup MUST be sped up... can't rely on whois if domain doesnt exist - will be damned slow...

		$ns_domain = $this->Domain->nameserver($domain);
		$ns_master = $this->Domain->nameserver($this->Multisite->prodhost);

		error_log("FOUND NAMESERVER FOR $domain TO BE $ns_domain, MY MASTER = $ns_master");

		$complete = ($ns_master != "" && $ns_domain == $ns_master);
		return $complete;
	}
	/*
	*/

	function manager_edit()
	{
		$this->admin_edit();
	}


	function admin_index() # Widget for account page.
	{
		return $this->redirect(array('action'=>'view'));
		/*

		# Get accurate version, despite session out of date.
		$this->Site->id = $this->get_site_id();
		$domain = $this->Site->field("domain");
		$this->set("domain", $domain);

		if(!empty($domain))
		{
			# Check to see if nameservers point to ours... (setup they need to do on their end)
			$domain_ns = $this->Domain->nameserver($domain);
			$master_ns = $this->Domain->nameserver($this->master_server);

			$dns_incomplete = ($domain_ns != $master_ns);
			$this->set("dns_incomplete", $dns_incomplete);
		}
		*/
	}

	function admin_setup() {  # Needed for view.

	}

	function admin_add()
	{
		# Just a form...
	}

	function admin_search()
	{
		#print_r($this->params);
		if(!empty($this->request->data))
		{
			$domain = $this->data['Site']['domain'];
			$this->set("domain", $domain);

			# Verify first that it's VALID!
			$this->Site->set($this->data); # FUCKING SET FIELDS FIRST!
			if($this->Site->validates(array('fieldList'=>array('domain'))))
			{
				$available = $this->Domain->available($domain);
				#error_log("DOMAIN!!!! = $domain, AVAIL=$available");
				$this->Domain->logout();
				$this->set("available", $available);
				$this->set("valid", true);
			}
		}
	}

	function admin_edit() # Form to ask for dns...
	{
		$setup = !empty($this->request->named['setup']) ;

		if(!empty($this->request->data))
		{
			$this->Site->id = $this->get_site_id();

			$domain = $this->request->data['Site']['domain'];
			$new = $this->Domain->available($domain);

			# !!! WE MUST make domain setup easy on them.... 

			error_log("PRE SAVE");
			if(!$this->Site->save($this->request->data, true, array('domain')))
			{
				# Validation errors (ie domain does not exist) should be passed along and rendered under fields....
				return $this->setError("Could not save your domain name");
			} else {
				error_log("POST SAVE");
				#$this->Json->notify('Your domain name has been saved.');
				#return $this->Json->render('admin_view');
				# Need to redirect so content is updated from session.

				# XXX TODO make sure we have a DNS zone for this domain - may exist already, may not.


				error_log("DOM2make=$domain");

				if(Configure::read("prod"))
				{
					$this->Domain->create_zone($domain);
				}

				error_log("CREATED ZONE=$domain");

				# XXX if added from setup, should get email of further setup.

				# Message should 

				if(!$this->_check_domain_complete($domain))
				{
					$vars = array(
						'domain'=>$domain
					);
					$this->sendSiteOwnerEmail("Your New Domain Name Needs Configuration", "sites/domain", $vars);
					error_log("INCOMPLETE DNS");
					$flash = "Your domain has been added to the system. An email has been sent with further instructions to complete your domain configuration before it can be used.";
					$this->setSuccess($flash); # Should be nice, so they dont freak out.
				} else {
					error_log("FINISHED DNS");
					$flash = "Your domain name has been added to your website and should be ready to use shortly. ";
					$this->setSuccess($flash);
				}

				$vars = array('domain'=>$domain, 'new'=>$new);

				Configure::write("Dns.self_register", false); # FOR NOW...

				# REGISTER DOMAIN IF NEW.... AND BILL THEM....
				if($new && Configure::read("Dns.self_register"))
				{
					# Make sure they are paying customers FIRST!!!!
					if(!$this->Multisite->get("stripe_id"))
					{
						return $this->setWarn("Please upgrade to a paid account so we can properly charge you for your domain registration.","/admin/billing");
					} else if($this->Domain->register($domain)) { # CHARGE
						# This is a $10 YEARLY charge, ie a different kind of subscription
						# We have to then re-register 
						# when they cancel site, this subscription should be terminated as well. XXX TODO
						$data = array('domain' => $domain);
						if(($result = $this->StripeBilling->saveYearlyDomainSubscription($data)) !== true)
						{
							$this->setError("We had trouble charging your account for the domain registration. Please make sure your payment information is accurate. $result");
							$vars['billing_failure'] = $result;
						} else { # ALL IS WELL!

						}
					} else { 
						$this->setError("Sorry, we had trouble registering this domain. We will take a look at get back to you shortly.");
						$vars['failure'] = true;
					}
				} # ELSE, existing or Manual register, on my end...

				# Inform me of domain!
				$this->managerEmail("Domain Registration", "Dns.domain_added", $vars);

				error_log("ABOUT TO REDIRECT");
				$url = array('admin'=>1,'action'=>'view');
				if(!empty($setup)) { $url['setup'] = 1; }
				return $this->redirect($url);
				#return $this->Json->redirect(array('plugin'=>'sites','controller'=>'sites','action'=>($setup ? 'setup' : 'view')));
			}
		}
		$this->request->data = $this->Site->read(null, $this->get_site_id()); # So autofills
	}

	function admin_edit_REGISTER($register = false)
	{
		if(!empty($this->data['Site']['domain']))
		{
			$expDate = null;
			if($register && $this->Multisite->prod)
			{
				if($expDate = $this->Domain->register($domain))
				{
					# Bill their card.
					$dnscharge = 15;
					$dnsdesc = "Hopeful Press Domain Registration: {$this->data['Site']['domain']}";

					# do not let them register if they are a TRIAL customer !
					# *** better to charge extra (DISCLAIM) for first month ($40 if domain), rather than absorb such significant costs when trying to get on financial feet!
					# as long as I'm clear and up front! $25/mo + $15 annual domain registration fee

					$this->Billing->charge_once($dnscharge, $dnsdesc);
					
				} else {
					$this->setFlash("Unable to register domain $domain");
					return;
				}
				$this->Domain->logout();
			}

			# Get routing to recognize.
			$this->Site->id = $this->get_site_id();
			$this->Site->saveField("domain", $this->data['Site']['domain']);
			$this->Site->saveField("domain_expiration", $expDate);

			# Tell it to reload session data...
			$this->set_current_site($this->Site->id);

			# Now make sure dns zone exists....
			if($this->Multisite->prod)
			{
				$this->Domain->create_zone($this->data['Site']['domain']);
			}

			$this->redirect(array('action'=>'index')); 
		}
		$this->data = $this->get_site();
	}


}
