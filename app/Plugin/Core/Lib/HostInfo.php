<?
# Should be separated from site specific queries...
# Global library to gather hostname, domain, etc.
class HostInfo
{
	static function fqdn()
	{
		return preg_replace("/^www[.]/", "", self::host());
	}

	static function hostparts()
	{
		$hostname = 'www';
		$domain = $fqdn = self::fqdn();
		# match hp.malysoft.com, www.hopefulpress.com, www.hp.m.com, h.com
		$fqdn = preg_replace("/^www[.]/", "", $fqdn); # Remove www

		# If matches any default domain, ie portal.malysoft.com, dev.hopefulpress.com, etc.... return null for hostname.
		if(in_array($fqdn, self::default_domains()))
		{
			return array(null,$fqdn);
		}

		$parts = split("[.]", $fqdn);
		if(count($parts) > 2) {
			$hostname = array_shift($parts);
		}
		$domain = join(".", $parts);
		#error_log("H=$hostname, D=$domain");
		return array($hostname,$domain);
	}

	static function hostname()
	{
		list($hostname,$domain) = self::hostparts();
		return $hostname;
	}

	static function domain()
	{
		list($hostname,$domain) = self::hostparts();
		return $domain;
	}

	static function ip($hostname = null)
	{
		if(empty($hostname)) # Current IP client connected to
		{
			return !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : null;
		} else { # IP address for host given
			return gethostbyname($hostname);
		}
	}


	/*
	public static $prod = false;

	static function hostparts()
	{
		$parts = split(".", gethostname());
		$hostname = count($parts) > 2 ? array_shift($parts) : null;
		$domain = join(".", $parts);
		return array($hostname,$domain);
	}
	static function domain()
	{
		list($hostname,$domain) = self::hostparts();
		return $domain;
	}

	static function hostname()
	{
		list($hostname,$domain) = self::hostparts();
		if($hostname == 'www') { $hostname = null; }
		return $hostname;
	}

	static function hostdomain() # Get accurate site info, possibly existing if in session and haven't changed sites.
	{
		$hostname = $domain = null;

		$server_host = preg_replace("/^www[.]/", "", self::host()); # So they don't accidentally put in
		#error_log("HOST=$server_host");

		$default_domain = self::default_domain();

		if($server_host == $default_domain) { $server_host = "www.$default_domain"; } # So we find 'www'., mainly for hopefulpress.com => www.hopefulpress.com

		if(preg_match("/(.*)[.]{$default_domain}/", $server_host, $matches)) # A subdomain, ecovineland.hopefulpress.com
		{
			$hostname = $matches[1];
		} else { #  Something else, probably a full domain, www.ecovineland.com
			$domain = preg_replace("/^www[.]/", "", $server_host); # Get rid of 'www', if there.
		}
		return array($hostname,$domain);
	}
	*/

	static function default_domains()
	{
		$domains = Configure::read("default_domains");
		if(!is_array($domains))
		{
			return split(",", $domains); # Comma separated ok.
		}
		return $domains;
	}

	static function default_domain() # Based on HTTP_HOST
	{
		$domains = self::default_domains();
		$default_domain = $domains[0];
		foreach($domains as $domain)
		{
			if(preg_match("/$domain/", self::host()))
			{
				$default_domain = $domain;
			}
		}
		# MUST assume that production system could be hp.com OR any number of domains....
		# dev only if malysoft.com ! everything else is LIVE! for billing,etc.
		return $default_domain;
	}

	static function host()
	{
		if(!empty($_SERVER['REDIRECT_HOSTNAME'])) # HTTPS/SSL mapping
		{
			return join(".",array($_SERVER['REDIRECT_HOSTNAME'], $_SERVER['HTTP_HOST']));
		} else if(!empty($_SERVER['HTTP_HOST'])) {
			return $_SERVER['HTTP_HOST'];
		} else if (!empty($_ENV['HTTP_HOST'])) { 
			return $_ENV['HTTP_HOST'];  
		} else { # CLI, etc.
			$domain = shell_exec("domainname");

			# Running from a shell, we have to guess and fake being in production/test mode
			# based on the current host's IP address.... or domain var set

			$hostname = gethostname();

			return !empty($hostname) ? "$hostname.$domain" : $domain;
			# Get full name, so use in emails, etc is relevent to their site.
		}
	}

	static function get_hw_ips()
	{
		$addresses = trim(shell_exec("/sbin/ifconfig -a | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'"));
		return explode("\n", $addresses);
	}

	static function get_dns_ip($hostname)
	{
		$dns = dns_get_record($hostname, DNS_A);
		return !empty($dns) ? $dns[0]['ip'] : null;
	}

	# Whether we're on a site or not... (routing will go to marketing site OR specific site)
	static function site_specified()
	{
		$http_host = self::host();
		$domains = join("|", self::default_domains());

		return !preg_match("/^(www[.])?($domains)/", $http_host);
	}

	static function geoip($ip = null)
	{
		if(empty($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }
		$rc = App::import("Vendor", "Tracker.geoip");
		$rc = App::import("Vendor", "Tracker.geoipcity");
		$gi = geoip_open(APP."/Plugin/Tracker/Vendor/GeoLiteCity.dat", GEOIP_STANDARD);
		$result = geoip_record_by_addr($gi, $ip);
		geoip_close($gi);
		if(empty($result)) { return array(); }
		$data = get_object_vars($result);
		return $data;
	}

	static function whois($ip = null, $key = null)
	{
		if(empty($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }

		App::import("Vendor", "Core.Whois",
			array('file'=>'phpwhois'.DS.'whois.main.php'));

		$whois = new Whois();
		$result = $whois->Lookup($ip,false);

		if(empty($key)) { return $result; }

		return $key  ? Set::extract($key, $result) : $result;

		/*
		$whois_lines = split("\n", $whois);
		foreach($whois_lines as $line)
		{
			if(preg_match("/([^:]+):\s+(.+)$/", $line, $match))
			{
				$key = $match[1];
				$value = $match[1];
				if(!empty($whois_hash[$key]))
				{
					$whois_hash[$key] .= "; ";
				} else {
					$whois_hash[$key] = "";
				}
				$whois_hash[$key] .= $value;
			}
		}

		return $whois_hash;
		*/
	}

	function domain_ready($domain)
	{
		$server = self::default_domain();

		if(preg_match("/malysoft/", $server)) { return true; }

		# For now, hardcode, only sense there is.
		if(empty($domain)) { return false; }
		# Check to see if dns servers match ours....
		$domain_ns = dns_get_record($domain, DNS_NS);
		$server_ns = dns_get_record($server, DNS_NS);

		$domain_nslist = Set::extract("/target", $domain_ns); # We might get 4
		$server_nslist = Set::extract("/target", $server_ns);

		if(empty($server_nslist)) { return false; }

		if(empty($domain_ns) || empty($server_ns)) { return false; }
		return in_array($server_nslist[0], $domain_nslist);
		# Just get first domain ns server being somewhere in our list....
	}
}
?>
