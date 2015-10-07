<?

App::uses('Helper', 'View');

class CoreAppHelper extends Helper
{
	function getVar($var)
	{
		return $this->_View->getVar($var);
	}

	function user($fullKey = null) # Can pass 'id', 'User.id', 'UserSetting.field', etc.
	{
		$user = $this->Session->read("Auth.User");
		list($model,$key) = pluginSplit($fullKey);

		# If no explicit model, try 'User', IF key not directly set in var. (worst case, will be null as expected)
		if(empty($model) && !isset($user[$key]))
		{
			$model = 'User'; # Default model
			$fullKey = "$model.$key";
		}
		return $this->Session->read( !empty($key) ? "Auth.User.$fullKey" : "Auth.User" );
	}

	function can($what,$where=null)
	{
		return $this->can_edit($where); # For now.
	}



	function can_edit($data) { return true; } # Implemented at app level.

	function current_site($key = null)
	{
		$site = $this->getVar("current_site");
		return !empty($key) ? $site['Site'][$key] : $site;
	}


	function me()
	{
		return $this->user("id");
	}

}
