<?php
#App::uses('StripeAppController', 'Sites.Controller');
class AuthController extends AppController {
	var $components = array('Stripe.StripeBilling','Stripe.Stripe');
	var $uses = array('Stripe.StripeCredential');

	function user_login()
	{
		$this->setAction("user_oauth");
	}

	function user_logout()
	{
		$this->StripeCredential->id = $this->StripeCredential->first_id();
		$this->StripeCredential->delete();
		$this->setSuccess("You've been successfully signed out of Stripe", $this->referer("/donation"));
	}

	function user_oauth()
	{
		$referer = $this->referer(null, true); # FULL url, ie rescue.hp.com/donation
		if(empty($referer)) { $referer = "/"; } # FAIL SAFE
		$this->Session->write("Stripe.redirect", $referer);
		# TODO https someday

		$default_domain  = HostInfo::default_domain();

		$redirect_uri = "https://{$default_domain}/stripe/auth/response";

		$hostname = $_SERVER['HTTP_HOST']; # So we know where to go back to after authorization
		$client_id = Configure::read("Stripe.client_id");
		$this->redirect("https://connect.stripe.com/oauth/authorize?response_type=code&client_id=$client_id&scope=read_write&redirect_uri=$redirect_uri&state=$hostname");
	}

	# TODO Handling de-authorization (removing record) XXX

	function complete() # Now that we're back on the site, go back to page we left for auth.
	{
		$redirect = $this->Session->read("Stripe.redirect");
		$this->Session->delete("Stripe.redirect");
		if(!empty($redirect))
		{
			$this->redirect($redirect);
		} else {
			$this->error("Referer lost and could not get back to site.");
		} # ELSE, failure page.
		# Maybe go to homepage with flash, i dunno..
	}

	function response()
	{
		error_log("RAW REQUEST=".print_r($this->request,true));
		$response = $this->request->query;#['query'];
		error_log("RESPONSE WE GOT=".print_r($response,true));

		if(!empty($response['error']))
		{
			return $this->setError($response['error_description']);
		} else { # Return struct.
			# Take hostname/domain, figure out site_id.
			$server = !empty($response['state']) ? $response['state']  : null;

			error_log("SERVER=$server");

			if(empty($server)) {
				return $this->error("Did not find site information");
			}

			$site_id  = $this->Multisite->getHostId($server); 
			error_log("SERVER=$server, SITE_ID=$site_id");
			if(empty($site_id))
			{
				return $this->error("Could not find site ID");
			}

			# Now get credential details.
			$authcode = $response['code'];

			App::uses("HttpSocket", "Network/Http");
			$socket = new HttpSocket();

			$mode = Configure::read("Stripe.mode");
			$client_secret = Configure::read("Stripe.{$mode}Secret");
			$client_id = Configure::read("Stripe.client_id");

			$raw = $socket->post("https://connect.stripe.com/oauth/token", array('code'=>$authcode,'client_secret'=>$client_secret,'grant_type'=>'authorization_code','client_id'=>$client_id));
			$credentials = get_object_vars(json_decode($raw)); # AHA

			error_log("POSTING TO =CODE=$authcode, CLIENT_SECRET=$client_secret, CLIENT_ID=$client_id");
			error_log("RESPONSE_INNER=".print_r($credentials,true));


			if(!empty($credentials['error']))
			{
				$error = $credentials['error_description'];
				error_log("ERROR!!!!!!!!!! ($error)");
				return $this->error("Could not sign in to Stripe: ".$error);
			} else {
				error_log("OBJECT!!!!!!!!!!");

				$credentials['site_id'] = $site_id; # SAVE

				$this->StripeCredential->set($credentials);
				if(!$this->StripeCredential->save())
				{
					$this->error("Could not save credentials: ".$this->DonationCredential->errorString());
				}
			}
		}
		$this->redirect("http://$server/stripe/auth/complete"); # Now back to proper url.
	}

	function error($string)
	{
		$this->set("error", $string);
		$this->render();
	}

}
