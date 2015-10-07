<?
App::uses('AppHelper','View/Helper');
class SiteHelper extends AppHelper 
{
	# DONT FUCKING SET helpers unless full list!

	function get($key, $default = null)
	{
		$value = $this->Session->read("CurrentSite.Site.$key");
		if(empty($value)) { $value = $default; }
		return $value;
	}

	function owner() { return $this->get("user_id"); }

	function is_owner($who)
	{
		if(empty($who)) { return false; }
		return ($who == $this->get("user_id"));
	}

}
