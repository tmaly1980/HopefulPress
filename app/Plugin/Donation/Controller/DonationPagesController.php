<?
App::uses("SingletonController", "Singleton.Controller");
class DonationPagesController extends SingletonController
{
	var $components = array();#'Stripe.StripeBilling');
	var $uses = array('Donation.DonationPage','Donation.Donation');#,'Stripe.StripeCredential');
	var $helpers = array('Stripe.Stripe');

	var $rescue_required = true;

	function view($id=null)
	{
		$this->track();
		if(!empty($this->request->query['initialized']))
		{
			$this->setSuccess("You've been successfully signed in to Stripe and can now accept donations on your website!");
		}

		# Cant get ssl cerrtificate until business license is filed...
		#########$this->require_https(); # FORCE SSL, goes to https://hostname.hopefulpress.com (wildcard certificate)
		# After donation, go back to non-ssl (passed to paypal)
		# Also interpret all links

		$this->set("donationsEnabled", !empty($this->StripeCredential) ? $this->StripeCredential->count() : null);

		return parent::view($id);
	}

	/*
	#XXX TODO
	function deauthorize() # Process disconnect from stripe
	{
		# Make sure data we get matches data in system (not fraudulent removal)
		$credentials = $this->DonationCredential->read();
		#$passed = 
	}
	*/


}
