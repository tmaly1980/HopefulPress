<?php
App::uses("Paypal", "Paypal.Lib");

class AuthController extends AppController {
	var $uses = array('Paypal.PaypalCredential');

	function user_edit() # Update credentials. For now, just need their email.
	{
		if(!empty($this->request->data))
		{
			if($this->PaypalCredential->save($this->request->data))
			{
				#$redirect = $this->Session->read("Paypal.redirect");
				#$this->Session->delete("Paypal.redirect");
				if(empty($redirect)) { $redirect = '/donation'; }
				return $this->setSuccess("Your Paypal account has been set up",$redirect);
			} else {
				return $this->setError("Could not save Paypal account information: ".$this->PaypalCredential->errorString());
			}
		} else {
			#$referer = $this->referer(); # FULL url, ie rescue.hp.com/donation
			#if(empty($referer)) { $referer = "/donation"; } # FAIL SAFE
			#$this->Session->write("Paypal.redirect", $referer);
		}
		$this->request->data = $this->PaypalCredential->first();
	}

	function user_login()
	{
		$this->setAction("user_oauth");
	}

	function user_logout()
	{
		$referer = $this->referer(null, true); # FULL url, ie rescue.hp.com/donation
		if(empty($referer)) { $referer = "/"; } # FAIL SAFE
		$this->Session->write("Paypal.redirect", $referer);

		$this->PaypalCredential->id = $this->PaypalCredential->first_id();
		$this->PaypalCredential->delete();
		$this->complete(); # Redirect # Redirect
	}

	function user_oauth()
	{
		$referer = $this->referer(null, true); # FULL url, ie rescue.hp.com/donation
		if(empty($referer)) { $referer = "/"; } # FAIL SAFE
		$this->Session->write("Paypal.redirect", $referer);
		# TODO https someday

		$default_domain  = HostInfo::default_domain();

		$redirect_uri = "https://{$default_domain}/paypal/auth/response";

		$hostname = $_SERVER['HTTP_HOST']; # So we know where to go back to after authorization
		$client_id = Configure::read("Paypal.client_id");
		$this->redirect("https://connect.paypal.com/oauth/authorize?response_type=code&client_id=$client_id&scope=read_write&redirect_uri=$redirect_uri&state=$hostname");
	}

	# TODO Handling de-authorization (removing record) XXX

	function complete() # Now that we're back on the site, go back to page we left for auth.
	{
		error_log("WHAT WE GOT=".print_r($this->request,true));

		$redirect = $this->Session->read("Paypal.redirect");
		$this->Session->delete("Paypal.redirect");
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

			$mode = Configure::read("Paypal.mode");
			$client_secret = Configure::read("Paypal.{$mode}Secret");
			$client_id = Configure::read("Paypal.client_id");

			$raw = $socket->post("https://connect.paypal.com/oauth/token", array('code'=>$authcode,'client_secret'=>$client_secret,'grant_type'=>'authorization_code','client_id'=>$client_id));
			$credentials = get_object_vars(json_decode($raw)); # AHA

			error_log("POSTING TO =CODE=$authcode, CLIENT_SECRET=$client_secret, CLIENT_ID=$client_id");
			error_log("RESPONSE_INNER=".print_r($credentials,true));


			if(!empty($credentials['error']))
			{
				$error = $credentials['error_description'];
				error_log("ERROR!!!!!!!!!! ($error)");
				return $this->error("Could not sign in to Paypal: ".$error);
			} else {
				error_log("OBJECT!!!!!!!!!!");

				$credentials['site_id'] = $site_id; # SAVE

				$this->PaypalCredential->set($credentials);
				if(!$this->PaypalCredential->save())
				{
					$this->error("Could not save credentials: ".$this->DonationCredential->errorString());
				}
			}
		}
		$this->redirect("http://$server/paypal/auth/complete"); # Now back to proper url.
	}

	function error($string)
	{
		$this->set("error", $string);
		$this->render();
	}

	# Get proper token given client_id/secret, send back to client (browser)
	function tokenize()
	{
		# TODO paypalCredential
		# HARDCODE FOR NOW
		$config = Configure::read("Paypal");
		$this->Paypal = new Paypal($config);
		$token = $this->Paypal->getOAuthAccessToken();
		$this->Json->set("token", $token);
		$this->Json->set("storeCreditCardUrl", $this->Paypal->storeCreditCardUrl()); # So we know live/sandbox
		return $this->Json->render();
	}

}
