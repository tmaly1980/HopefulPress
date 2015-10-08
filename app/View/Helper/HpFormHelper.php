<?
App::uses('CoreFormHelper','Core.View/Helper');
class HpFormHelper extends CoreFormHelper 
{
	function create($model=null,$url=array(),$args=array())
	{
		# Assume if we don't want to preserve the rescue passed on the page, to use STRING url's
		if(is_array($url) && ($rescuename = Configure::read("rescuename")) && !isset($url['rescue']))
		{
			$url['rescue']  = $rescuename;
		}
		return parent::create($model,$url,$args);
	}
}
