<?
# Query, register, etc domains with OnlineNIC.

App::import('Xml');
#require_once("Services/Linode.php"); # Needed since under /usr/share/php

#App::import('Vendor', 'Linode',array('file'=>'Services/Linode.php'));
App::import('Vendor', 'linode'); # NEW Mar 9 2015

#error_log("IMPORTED DomainManager OK");

class DomainManager
{
	var $test = true;
	var $live_test = false; # if live system, don't test.
	var $port = 30009;
	var $account_id = null;
	var $password = null;
	var $url = null;
	var $socket = null;
	var $chksum = null;
	var $debug = array();
	var $connected = false;
	var $error = null;


	function __construct()
	{
		Configure::load('Dns.domain'); # Login, etc.

		# set to live if on live system. and live_test=>false
	}

	function random($length = 8, $hex = false) # Random codes, whether password, unique filenames, registration code, etc...
	{
		$chars = array();
		$last = $hex ? "f" : 'z';

		for ($i = ord('a'); $i < ord($last); $i++)
		{
			$chars[] = chr($i);
			$chars[] = strtoupper(chr($i));
		}
		for ($i = ord('0'); $i < ord('9'); $i++)
		{
			$chars[] = chr($i);
		}

		shuffle($chars); # randomize.

		$code = "";
		for ($ix = 0; $ix < $length; $ix++)
		{
			$code .= $chars[ rand(0, count($chars)-1) ];
		}

		return $code;
	}


	function _read()
	{
		# NO DANG newline to stop with fgets()
		# So need to stop at end of response tag....
		$xmlstring = "";
		while(!feof($this->socket) && !preg_match('@</response>@', $xmlstring))
		{
			$chars = fread($this->socket,1);
			$xmlstring .= $chars;
		}
		# since no EOF given.
		$this->debug[] = "READING $xmlstring";

		#error_log("XML=".print_r($xmlstring,true));

		#$xml = new Xml($xmlstring);
		#$xmlarray = $xml->toArray();
		$xmlarray = Xml::toArray(Xml::build($xmlstring));

		return !empty($xmlarray['Response']) ? $xmlarray['Response'] : null;
	}

	function _write($xml)
	{
		$header = '<'.'?xml version="1.0" encoding="UTF-8" ?'.'>';
		$this->debug[] = "WRITING $xml";
		return fputs($this->socket, $header.$xml);
	}

	function _connect()
	{
		if($this->connected) { return true; }

		$test = $this->test;
		# Check if live system and live test opt.

		if($test)
		{
			$this->server = "218.5.81.149";
			$this->account_id = "135610";
			$this->password = "654123";
		} else {
			#$this->server = "www.onlinenic.com";
			$this->server = "72.233.76.106"; # name resolution issue????
			$this->account_id = Configure::read("Domain.account_id");
			$this->password = Configure::read("Domain.password");
		}

		$this->socket = fsockopen($this->server, $this->port, $errno, $errstr, 30);
		$hello = $this->_read();
		if(empty($hello))
		{
			$this->debug[] = "Didnt get proper hello";
			return false;
		}

		$this->debug[] = "HELLO: $hello";

		$this->connected = true;

		return $this->execute("client", "login",array('clid'=>$this->account_id),false);
	}

	function chksum($command, $trans_id, $params = array()) # For commands....
	{
		$param_string = "";
		foreach($params as $k=>$v) # Allow for multiple params of same name, ie dns (1 and 2)
		{
			$param_string .= is_array($v)?join("", $v):$v;
		}
		$chksum_pre = ($this->account_id . md5($this->password) . $trans_id . strtolower($command).$param_string);
		$this->debug[] = "CHCKSUM($command)=$chksum_pre, ";
		return md5($chksum_pre);
	}

	function success($response)
	{
		$code = !empty($response['code']) ? $response['code'] : null;
		return ($code >= 1000 && $code < 2000);
	}

	function params($params = array())
	{
		$params_xml = array();
		foreach($params as $k=>$v)
		{
			if(is_array($v))
			{
				foreach($v as $vo)
				{
					$params_xml[] = "<param name='$k'>$vo</param>";
				}
			} else {
				$params_xml[] = "<param name='$k'>$v</param>";
			}
		}

		return !empty($params_xml) ? "<params>".join("", $params_xml)."</params>" : null;
	}

	function command($category, $action, $params = array(), $chksum_params = true)
	{
		$transaction_id = $this->random(24, false); # Unique per request.
		$csp = array();
		if($chksum_params !== false)
		{
			foreach($params as $k=>$v)
			{
				# We can pass true for all, false for none
				# and a list for specific ones.
				if($chksum_params === true || (is_array($chksum_params) && in_array($k, $chksum_params)))
				{
					$csp[$k] = $v;
				}
			}
		}
		$chksum = $this->chksum($action, $transaction_id, $csp);
		$params = $this->params($params);
		$uc_action = ucfirst($action); # fixed 'no login'....
		$cmd = "<request><category>$category</category><action>$uc_action</action>"
			.$params.
			"<cltrid>{$transaction_id}</cltrid><chksum>{$chksum}</chksum></request>";
		return $cmd;
	}

	function logout($data = null)
	{
		$this->execute("client","logout");
		# Send logout command.

		fclose($this->socket);

		$this->connected = false;

		return $data; # What to send back to controller....
	}

	function execute($category, $action, $params = array(), $chksum_params = true)
	# ORDER OF PARAMS MATTER, SO AS TO DO CHKSUM PROERLY....
	#
	{
		if(!$this->connected && !$this->_connect())
		{
			return false;
		}
		$command = $this->command($category, $action, $params, $chksum_params);
		$this->_write($command);
		$response = $this->_read();
		# If bad syntax, etc return false, else return data.
		if(!$this->success($response))
		{
			$this->debug[] = "$category/$action FAILED ({$response['code']}): ".$response['msg'];
			return false;
		}
		return $response;
	}

	function domaintype($domain)
	{
		$domainparts = preg_split("/[.]/", strtolower($domain));
		$ext = count($domainparts) ? $domainparts[count($domainparts)-1] : null;
		$this->debug[] = "DOM=$domain, EXT=$ext";
		$domaintypemap = array(
			'com'=>'0',
			'org'=>'807',
			'us'=>'806',
			'eu'=>'902',
			'me'=>'906'
		);
		$this->debug[] = ", MAP=".$domaintypemap[$ext];
		return isset($domaintypemap[$ext]) ? $domaintypemap[$ext] : false;
	}

	function data($response) # Assumes <data name='foo'>bar</data>
	{
		if(!empty($response['resData']))
		{
			$xmldata = $response['resData'];
			# Re-create array.
			$data = array();
			# Convert Data[0][value/name] to name=>value
			foreach($xmldata as $node)
			{
				$key = $node['name'];
				$value = $node['value'];
			}
			# XXX 
			return $data;
		}
		return false;
	}

	function abort()
	{
		$this->logout();
		return false;
	}

	function info($domain)
	{
		$domaintype = $this->domaintype($domain);
		if($domaintype === false) { 
			$this->debug[] = "INVALID DOMAIN $domain";
			return false;
		}

		$params = array(
			'domaintype'=>$domaintype,
			'domain'=>$domain,
		);
		$resp = $this->execute("domain", "InfoDomain", $params);
		if(!$resp) { return $this->abort(); }

		$data = $this->data($resp);

		# DONE.
		return $data;
	}

	function available($domain) # Need something OFF Linode (universal), so can test on home system...
	{
		return !checkdnsrr($domain, 'A');
	}

	function XXXavailable($domain)
	{
		$domaintype = $this->domaintype($domain);
		if($domaintype === false) { 
			$this->debug[] = "INVALID DOMAIN $domain";
			return false;
		}

		$params = array(
			'domaintype'=>$domaintype,
			'domain'=>$domain,
		);
		$resp = $this->execute("domain", "CheckDomain", $params);
		if(!$resp) { return $this->abort(); }

		$data = $this->data($resp);
		return empty($data['avail']);

		# DONE.
		#return $this->logout($data);
	}

	# XXX TODO DOMAIN RENEWAL.... 

	function register($domain)
	{
		$ns1 = 'ns1.linode.com';
		$ns2 = 'ns2.linode.com';
		$password = 'worldpeace1234';

		$domaintype = $this->domaintype($domain);
		if($domaintype === false) { 
			$this->debug[] = "INVALID DOMAIN $domain";
			return false;
		}
		
		$reginfo = array(
			'name'=>"Hopeful Press",
			'org'=>"Hopeful Press",
			'email'=>'admin@hopefulpress.com',
			'password'=>$password,
			// Other reg-only stuff...
			'AppPurpose'=>'P1',
			'NexusCategory'=>'C21',
		);

		$contactinfo = array(
			'name'=>"Hopeful Press",
			'org'=>"Hopeful Press",
			/* See if we can get away with not entering....
			'country'=>'US', 
			'province'=>'NJ',
			'city'=>'Elmer',
			'street'=>'1565 Highway 77',
			'postalcode'=>'08318',
			'voice'=>'+1.8568960229',
			*/
			'email'=>'admin@hopefulpress.com',
			'password'=>$password,
		);
		$md5 = array('name','org','email');

		$regID = $this->create_contact($domain, $reginfo, $md5);
		$adminID = $this->create_contact($domain, $contactinfo, $md5);
		$techID = $this->create_contact($domain, $contactinfo, $md5);
		$billingID = $this->create_contact($domain, $contactinfo, $md5);

		# REQUIRES contactID's for registrant, admin, tech, billing
		$params = array(
			'domaintype'=>$domaintype,
			'domain'=>$domain,
			'period'=>1,
			'dns'=>array($ns1,$ns2),
			'registrant'=>$regID,
			'admin'=>$adminID,
			'tech'=>$techID,
			'billing'=>$billingID,
			'password'=>$password,
		);
		$resp = $this->execute("domain", "CreateDomain", $params);
		if(!$resp) { return $this->abort(); }

		$data = $this->data($resp);
		return !empty($data['exDate']) ? $data['exDate'] : null;
		# When it expires.
	}

	function create_contact($domain, $contactinfo, $md5 = array())
	{
		$domaintype = $this->domaintype($domain);
		$resp = $this->execute("domain", "CreateContact", $params, $md5);
		if(!$resp) { return $this->abort(); }

		return !empty($data['contactid']) ? $data['contactid'] : null;
	}

	function whois($domain)
	{
		App::import("Vendor", 'Core.Vendor', array('file'=>'phpwhois'.DS.'whois.main.php'));
		$whois = new Whois();
		$whois->deep_whois = true;
		return @$whois->Lookup($domain, false); # Just in case domain doesn't exist, don't barf, hide error with '@'
	}

	function nameserver($domain)
	{
		# Needs to get from whois() NOT from dns_get_record - will fail until dns is set (ie zone reloaded), which is supposed to be US.

		$whois = $this->whois($domain);
		#error_log("NAMESERVER CHECK, WHOIS FOR $domain=".print_r($whois,true));
		if(empty($whois)) { 
			error_log("NO WHOIS AVAILABLE ON $domain");
			return null; 
		}
		# Vendors/phpwhois/example.cli.php shows an example of the data structure
		$nameservers = !empty($whois['regrinfo']['domain']['nserver']) ? array_keys($whois['regrinfo']['domain']['nserver']) : array();
		#error_log("NS HOSTS=".print_r($nameservers,true));
		if(!empty($nameservers)) { 
			return $nameservers[0];
		}
		return null;

		/*
		$records = dns_get_record($domain, DNS_NS);
		if(empty($records)) { error_log("Couldnt resolve $domain"); return null; }
		foreach($records as $rec)
		{
			if($rec['type'] == 'NS')
			{
				return $rec['target'];
				# return first (primary)
			}
		}
		return null;
		*/
		
	}

	function create_zone($domain) # If doesnt exist, creates. else, silently returns.
	{
		#error_log("CREATING/VERIFYING DOMAIN ZONE FOR $domain");
		App::import("Vendor", "linode");

		$apikey = Configure::read("Domain.linode_apikey");

		$server_ip = gethostbyname("hopefulpress.com");

		try
		{
			#error_log("LOGGING INTO LINODE, APIKEY=$apikey");
			$linode = new Services_Linode($apikey);

			# See if already in list.
			$domainId = $this->get_zone_domainId($linode, $domain);
			#error_log("CURRENT DOMID=$domainId");

			# *** will silently bypass creation if already there...

			if(empty($domainId))
			{
				# God damned hash parameters, not args
				$zoneinfo = $linode->domain_create(array(
					"Domain"=>$domain,
					"Description"=>"Zone record for $domain", 
					"Type"=>'master', 
					"SOA_Email"=>'admin@hopefulpress.com'
				));
				$domainId = !empty($zoneinfo['DATA']['DomainID']) ? $zoneinfo['DATA']['DomainID'] : null;
				#error_log("DOMAINID=$domainId, ZONE=".print_r($zoneinfo,true));
				if(empty($domainId))
				{
					error_log($this->error = "Could not create zone: $domain");
					return false;
				}
			}

			# Create records.
			# *.domain.com  and domain.com (includes www.domain.com)
			#

			# http://www.linode.com/api/dns/domain.resource.list
			$entries = $this->getEntries($linode, $domainId);

			$addresses = !empty($entries['A']) ? $entries['A'] : null;
			$mails = !empty($entries['MX']) ? $entries['MX'] : null;

			$this->create_zone_address($linode, $domainId, $addresses, '*', $server_ip); # www, etc.

			$this->create_zone_address($linode, $domainId, $addresses, '', $server_ip); # domain.com
			$this->create_zone_mx($linode, $domainId, $mails, "mail", $domain);

		} catch(Services_Linode_Exception $e) {
			$this->error = $e->getMessage();
			error_log("LINODE create_zone: ".$this->error);
			return false;
		}
	}

	function getEntries($linode, $domainId)
	{
		$entries = $linode->domain_resource_list(array("DomainID"=>$domainId));
		# Group by type!
		$entriesList = array();
		foreach($entries['DATA'] as $entry)
		{
			$type = $entry['TYPE'];
			$name = $entry['NAME'];
			$resourceId = $entry['RESOURCEID'];

			if(empty($entriesList[$type])) { $entriesList[$type] = array(); }
			$entriesList[$type][$name] = $resourceId;
		}

		return $entriesList;
	}

	function create_zone_address($linode, $domainId, $entryList, $host, $server_ip)
	{
		$resId = !empty($entryList[$host]) ? $entryList[$host] : null;
		if(empty($resId)) # Update either way, just in case ip is wrong.
		{
			$rc = $linode->domain_resource_create(array("DomainID"=>$domainId, "Type"=>"A", "Name"=>$host, "Target"=>$server_ip));
		} else {
			$rc = $linode->domain_resource_update(array("ResourceID"=>$resId, "DomainID"=>$domainId, "Type"=>"A", "Name"=>$host, "Target"=>$server_ip));
		}
		#error_log("CREATE A ($host, IP+$server_ip)=".print_r($rc,true));
		return $rc;
	}

	function create_zone_mx($linode, $domainId, $mails, $host, $domain)
	{
		$resId = !empty($mails[$host]) ? $mails[$host] : null;
		if(empty($resId)) # Update either way, just in case ip is wrong.
		{
			$rc = $linode->domain_resource_create(array("DomainID"=>$domainId, "Type"=>"MX", "Name"=>$domain, "Target"=>$host));
		} else {
			$rc = $linode->domain_resource_update(array("ResourceID"=>$resId,"DomainID"=>$domainId, "Type"=>"MX", "Name"=>$domain, "Target"=>$host));
		}
		return $rc;
	}

	function get_zone_domainId($linode, $domain)
	{
		$domains = $linode->domain_list();
		$ds = Set::extract("/DATA[DOMAIN=$domain]/DOMAINID", $domains);
		return !empty($ds) ? $ds[0] : null;
	}

	function get_zone($linode, $domain)
	{
		$domains = $linode->domain_list();
		$ds = Set::extract("/DATA[DOMAIN=$domain]", $entries);
		return !empty($ds) ? $ds[0] : null;

		/*
		foreach($domains['DATA'] as $d)
		{
			if($d['DOMAIN'] == $domain)
			{
				return $d;
			}
		}
		return null; # Nothing relevent found.
		*/
	}

	
}
