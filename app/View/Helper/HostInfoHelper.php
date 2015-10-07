<?
App::uses("HostInfo", "Lib");

class HostInfoHelper extends AppHelper
{
	function __call($method, $args = array())
	{
		return call_user_func_array(array("HostInfo", $method), $args);
	}
}
