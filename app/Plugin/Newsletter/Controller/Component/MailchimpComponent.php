<?
App::uses("HttpSocket","Network/Http");

class MailchimpComponent extends Component
{
	var $controller = null;

	# Config read in AppController

	function initialize(Controller $controller)
	{
		$this->controller = $controller;
		return parent::initialize($controller);
	}

	function login($action = 'oauth')
	{
		$client_id  = Configure::read("Mailchimp.client_id");
		$url = Router::url(array('action'=>$action),true);
		$this->controller->redirect("https://login.mailchimp.com/oauth2/authorize?client_id=$client_id&redirect_uri=$url&response_type=code");
		# ? do we need to give an approved list of url's?
	}

	function logout()
	{
		if($this->controller->MailchimpCredential->id = $this->controller->MailchimpCredential->first_id())
		{
			$this->controller->MailchimpCredential->delete();
		}
	}

	function oauth()
	{
		error_log("GIVEN=".print_r($this->controller->request->query,true));

		# Take 'code' and query for 'access_token'

		if(!empty($this->controller->request->query['code']))
		{
			$code = $this->controller->request->query['code'];
			error_log("CODE=$code");
			$params  = array(
				'client_id'=>Configure::read("Mailchimp.client_id"),
				'client_secret'=>Configure::read("Mailchimp.client_secret"),
				'code'=>$code,
				'grant_type'=>'authorization_code',
				'redirect_uri'=>Router::url(array('action'=>'oauth'),true),# Why? Bad Request otherwise.
			);

			$result = $this->controller->http_post("https://login.mailchimp.com/oauth2/token", $params);
			error_log("TOKENING RESULT=".print_r($result,true));
			$json_result = json_decode($result,true);

			error_log("JSON_TOKEN=".print_r($json_result,true));

			if(!empty($json_result['access_token']))
			{
				$token = $json_result['access_token'];
				# Now get metadata (ie server info)

				$metadata_result = $this->controller->http_get("https://login.mailchimp.com/oauth2/metadata",null,array('header'=>array('Authorization'=>"OAuth $token")));
				$metadata  = json_decode($metadata_result,true);

				error_log("METADATA=".print_r($metadata_result,true));

				if(!empty($metadata['dc']))
				{
					$credentials = array('access_token'=>$token,'dc'=>$metadata['dc']);
					if($this->controller->MailchimpCredential->save($credentials))
					{
						return false;  # No errors.
					}
				} else if (!empty($metadata['error_description'])) {
					return $metadata['error_description'];
				} else {
					return 'Could not get server information';
				}
			} else if(!empty($json_result['error_description'])) { 
				return $json_result['error_description'];
			} else {
				return 'Could not complete sign in process';
			}
		} else if(!empty($this->controller->request->query['error'])) {
			return $this->controller->request->query['error'];
		}
		return 'Unknown error (no response)';
	}

	function credentials()
	{
		$credentials = $this->controller->MailchimpCredential->first();
		if(empty($credentials))
		{
			throw new InternalErrorException("Not logged in to Mailchimp");
		}
		error_log('CRED='.print_r($credentials,true));
		return array($credentials['MailchimpCredential']['access_token'],$credentials['MailchimpCredential']['dc']);
	}

	function socket()
	{
		list($apikey,$dc) = $this->credentials();
		if(empty($apikey))
		{
			$this->logout();
			return $this->controller->setError("Sorry, we couldn't find your sign in information. Please sign into MailChimp again.");
		}
		$socket = new HttpSocket();
		$socket->configAuth('Basic','doesntmatter',$apikey);
		error_log("AUTH=$apikey");

		return $socket;
	}

	function get($command, $params=array())
	{
		$socket = $this->socket();
		$response = $socket->get($this->url($command), $params);
		return $this->decode($response);
	}
	function post($command,$params=array())
	{
		$socket = $this->socket();
		$json_params = json_encode($params);
		$response = $socket->post($this->url($command), $json_params);
		return $this->decode($response);
	}
	function update($command,$params=array()) # Existing records.
	{
		$socket = $this->socket();
		$json_params = json_encode($params);
		$response = $socket->patch($this->url($command), $json_params);
		return $this->decode($response);
	}

	function decode($response)
	{
		error_log("RESPONSE=".print_r($response,true));
		$result = json_decode($response->body(),true);
		if($response->code == 404) # NOT FOUND, not catastrophic though.
		{
			return null;
		} else if($response->code >= 400) # Invalid
		{
			error_log("ERROR=".print_r($result,true));
			if(!empty($result['title']))
			{
				throw new InternalErrorException($result['title']);
			} else {
				throw new InternalErrorException('Unknown MailChimp error');
			}
		} 
		return $result;
	}

	function url($command)
	{
		list($apikey,$dc) = $this->credentials();
		if(empty($dc))
		{
			$this->logout();
			return $this->controller->setError("Sorry, we couldn't find your sign in information (server details). Please sign into MailChimp again.");
		}
		$host = "https://$dc.api.mailchimp.com/3.0";
		error_log("GETTING $host/$command");
		return "$host/$command";
	}

	#
	#
	#####################################################################
	# Convenience functions
	#
	#
	function subscribe($data,$listName='Subscribers',$permission=false)
	{ # Passed 'email','fname','lname'
		$data = array(
			'email_address'=>(is_array($data)?$data['email']:$data),
			'status' => (!empty($permission)?'subscribed':'pending'), # Should send opt-in email...
			'merge_fields'=>array(
				'FNAME'=>!empty($data['fname'])?$data['fname']:null,
				'LNAME'=>!empty($data['lname'])?$data['lname']:null,
			),
		);
		$list_id = is_numeric($listName) ? $listName : $this->get_list_id($listName);
		# Might be id, might be name.

		return $this->post("lists/$list_id/members",$data);
	}

	# Should remove from ALL lists...
	function unsubscribe($email)#,$listName='Subscribers')
	{
		$md5email = md5(strtolower($email));
		$lists = $this->get("lists");
		$count = 0;
		foreach($lists['lists'] as $l)
		{
			$lid = $l['id'];
			if($this->patch("lists/$lid/members/$md5email",array('status'=>'unsubscribed')))
			{
				$count++; # Found
			}
			# null means wasn't there.
		}
		return $count; # whether found or not.
	}

	function members($list_id,$total=500,$offset=0) # Get BIG list....
	{
		return $this->get("lists/$list_id/members",array('count'=>$total,'offset'=>$offset,'status'=>'subscribed'));
	}

	function get_list_id($listName='Subscribers')
	{
		$lists = $this->get("lists");
		if(empty($lists)) { return null; } # NONE

		foreach($lists['lists'] as $listDetails)
		{
			if($listDetails['name'] == $listName)
			{
				return $listDetails['id'];# FOUND IT
			}
		}
		return null;
	}

	function create_list($listName,$contact)
	{
		$data = array(
			'name'=>$listName,
			'contact'=>array(
				'company'=>$this->controller->Multisite->get("title"),
				'address1'=>$contact['address'],
				'city'=>$contact['city'],
				'state'=>$contact['state'],
				'zip'=>$contact['zip_code'],
				'country'=>(!empty($contract['country'])?$contract['country']:'United States'),
			),
			'permission_reminder'=>"You're receiving this email because you've communicated/worked with us",
			'email_type_option'=>true,
			'campaign_defaults'=>array(
				'from_name'=>$this->controller->Multisite->get("title"),
				'from_email'=>'notification@'.$this->controller->Multisite->get("hostname").".hopefulpress.com",
				'subject'=>$this->controller->Multisite->get('title')." Updates",
				'language'=>"en"
			),
		);
		return $this->post("lists",$data);
	}


}
