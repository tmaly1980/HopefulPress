<?
App::uses('CoreHtmlHelper','Core.View/Helper');
class HpHtmlHelper extends CoreHtmlHelper 
{
	var $helpers = array('Session','Form','Site','Text','Rescue'); # THIS NEEDS TO BE EXPLICIT??? who knows why. Something about _call being overwritten?

	# *** IF WE DONT WANT THE RESCUE PARSED IN A LINK (when the previous/loaded page has it set), GIVE AN ABSOLUTE STRING! ****
	# Otherwise, let's assume all links want rescue passed if available.

	function link($title, $url=null, $opts=array(), $confirmMessage = false)
	{
		if(($rescuename = Configure::read("rescuename")) && is_array($url) && !isset($url['rescue']) && !$this->Rescue->dedicated())
		{
			$url['rescue'] = $rescuename;
		}
			
		# Remove prefix if unwanted. (it's implied so we have to set proper false)
		if(is_array($url) && isset($url['prefix']) && empty($url['prefix']) && !empty($this->request->params['prefix']))
		{
			$url[$this->request->params['prefix']] = false;
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
