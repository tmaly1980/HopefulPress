<?
App::uses('CoreHtmlHelper','Core.View/Helper');
class HpHtmlHelper extends CoreHtmlHelper 
{
	var $helpers = array('Session','Form','Site','Text');

	# Todo, have rescue, transporter, volunteer, etc link methods....

	function rescue_link($title,$url=null,$opts=array(),$confirmMessage=false) # Preserves rescue info in url.
	{
		if(is_array($url))
		{
			if(isset($url['rescue'])) { $url['hostname'] = $url['rescue']; unset($url['rescue']); }

			if(!isset($url['hostname']) && !empty($this->request->params['hostname'])) # Rescue specific url, 
			{
				$url['hostname'] = $this->request->params['hostname']; # 
			}
		}
		return $this->link($title,$url,$opts,$confirmMessage);
	}

	function link($title, $url=null, $opts=array(), $confirmMessage = false)
	{
		Configure::load("projectable");
		$projectable_controllers = Configure::read("Projectable.controllers");

		# If controller is projectable, and in project, force all links to use project_id
		# We abort use of project_id if we go to a non-project controller (ie homepages)
		$parsedUrl = Router::parse(Router::url($url));
		$controller = !empty($parsedUrl['controller']) ? $parsedUrl['controller'] : null;
		if(in_array($controller, $projectable_controllers) && ($pid = Configure::read("project_id")) && !isset($url['project_id'])) # Bypass project if pass id/false
		{
			if(is_array($url))
			{
				if(!isset($url['project_id']))
				{
					$url['project_id'] = $pid;
				}
			} else {
				$url .= "/project_id:$pid";
			}
		}


		return parent::link($title, $url, $opts, $confirmMessage);
	}

	function url($url=null,$full=false)
	{
		# Catch links to be HTTP ONLY if HTTPS set.
		if(!empty($_SERVER['HTTPS']))
		{
			$path = Router::url($url);
			$hostname = Configure::read("hostname");
			$domain = Configure::read("default_domain");
			$url = "http://$hostname.$domain$path";
			#echo "URL=$hostname : $domain : $url";
		}
		return parent::url($url,$full);
	}

	function is_admin()
	{
		return $this->user("admin") || $this->user("manager") || $this->Site->is_owner($this->Site->me());
	}
}
