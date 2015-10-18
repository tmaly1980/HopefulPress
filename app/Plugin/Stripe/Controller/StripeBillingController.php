<?php
#App::uses('StripeAppController', 'Sites.Controller');
class StripeBillingController extends AppController {
	var $setup = false;
	var $components = array('Stripe.StripeBilling');
	var $uses = array();
	var $config = null;

	function index()
	{
		$this->redirect(array('admin'=>1,'action'=>'view'));
	}

	function admin_index()
	{
		$this->redirect(array('action'=>'view'));
	}

	function manager_edit() # Changing plan.
	{
		$old_plan = $this->rescue("plan");
			
		if(!empty($this->request->data))
		{
			$this->request->data['Rescue']['disabled'] = null; # re-enable.
			$this->request->data['Rescue']['trial'] = 0; # No longer.
			if(empty($old_plan) && !empty($this->request->data['Rescue']['plan']))
			{
				$this->request->data['Rescue']['upgraded'] = date("Y-m-d H:i:s");
			}

			if($this->Rescue->save($this->request->data, true,array('plan','disabled')))
			{
				$this->setSuccess("Plan has been updated",array('user'=>1,'action'=>'index'));
			} else {
				$this->setError("Could not update plan: ".$this->Rescue->errorString());
			}
		}
		Configure::load("Stripe.billing");
		$this->config = Configure::read("Billing");
		$this->set($this->config);
		$plans = Set::combine($this->config, "{n}.id", "{n}.name");
		$this->set("plans",$plans);
		$this->request->data = $this->Rescue->read();
	}

	function admin_disable()
	{
		if($plan = $this->rescue("plan") && $this->StripeBilling->validHostingSubscription())
		{
			if($error = $this->StripeBilling->cancelHostingSubscription())
			{
				return $this->setError($error,"/admin/billing");
			}
		}
		if($this->Rescue->saveField("disabled",date("Y-m-d H:i:s")))
		{
			return $this->setSuccess("Your website has been cancelled. You can always sign in again to restore it at any time.","/");
		}
		$this->setError("Could not disable your website","/admin/billing");
		# Add 'disabled' flag to site, force 'unavailable' page (but allow sign in, force to billing page, remove admin sidebar).
	}

	function admin_restore()
	{
		# If has a plan, re-instate billing.
		if($plan = $this->rescue("plan") && $this->StripeBilling->validCreditCard())
		{
			if(($error = $this->StripeBilling->hostingSubscription(array('plan'=>$plan))) && is_string($error))
			{
				return $this->setError($error);
			}
		} else { # If site is expired/past 30 days trial w/o plan, force them to upgrade
			return $this->setError("Please upgrade to a paid plan below to restore your website","/admin/billing");
		}
		if($this->Rescue->saveField("disabled",null))
		{
			return $this->setSuccess("Your website has been restored","/");
		}
		$this->setError("Could not restore your website","/admin/billing");
	}

	function admin_upgrade($plan='basic')
	{
		$old_plan = $this->rescue("plan");

		# If no credit card, prompt for payment & add plan.
		if(!$this->StripeBilling->validCreditCard())
		{
			$this->redirect(array('action'=>'edit',$plan));
		}

		# If already has credit card, add new plan then cancel existing plan (if any)
		$data = array('plan'=>$plan);
		if(($error = $this->StripeBilling->hostingSubscription($data)) && is_string($error)) # Will cancel existing and prorate if needed.
		{
			return $this->setError("Could not update plan: $error", "/admin/billing");
		}
		if(empty($old_plan) && !empty($plan))
		{
			$data['upgraded'] = date("Y-m-d H:i:s");
		}
		$data['disabled'] = null;
		$data['trial']  = 0;
		if($this->Rescue->save($data))
		{
			return $this->setSuccess("Plan successfully updated", "/admin/billing");
		}
		return $this->setError("Could not update account status", "/admin/billing");
	}

	function admin_view($id = null)
	{
		Configure::load("Stripe.billing");
		$this->config = Configure::read("Billing");
		$this->set($this->config);
		$this->set('plan', $this->rescue("plan"));
		$this->set('disabled', $this->rescue("disabled"));

		$subscription = $this->StripeBilling->hostingSubscription();
		$this->set("card", $this->StripeBilling->defaultCard());
	}

	function admin_setup() # Adding payment for first time, in site setup.
	{
		if(!empty($this->request->data['StripeBilling'])) # Process
		{
			if(($error = $this->StripeBilling->hostingSubscription($this->request->data['StripeBilling'])) && is_string($error))
			{
				return $this->setError($error);
			}
			/* No worries for now....
			if(!$this->rescue("completed"))
			{
				$this->redirect("/setup");
			} else { # ie restore after cancel, just ask for billing then to go admin home
			*/
				#$this->redirect("/admin");
				$this->redirect("/admin/billing");
			//}
		}
	}

	function admin_edit($plan=null)
	{
		#$this->require_https(); # DAMNIT.

		if(empty($plan)) { $plan = $this->rescue('plan'); }

		if(!empty($this->request->data['StripeBilling'])) # Process
		{
			error_log("FORM DATA=".print_r($this->request->data,true));
			$this->Rescue->id = Configure::read("site_id");
			if(($error = $this->StripeBilling->hostingSubscription($this->request->data['StripeBilling'])) && is_string($error)) # Could get object back.
			{
				$this->setError($error);
			} else {
				$old_plan = $this->rescue("plan");
				$data = array('plan'=>$plan,'disabled'=>null);
				if(empty($old_plan) && !empty($plan)) { $data['upgraded'] = date("Y-m-d H:i:s"); }

				$this->Rescue->save($data);
				$this->redirect(array('action'=>'view'));
			}
		}

		Configure::load("Stripe.billing");
		$this->config = Configure::read("Billing");
		$this->set($this->config);
		$this->set("plan", $plan);
	}

	function manager_index()
	{
		# XXX LIST ALL CUSTOMERS WITH STRIPE BILLING STATUS, HIGHLIGHT FAILED BILLING - contact directly for now (unlikely, ok!)
	}


}

