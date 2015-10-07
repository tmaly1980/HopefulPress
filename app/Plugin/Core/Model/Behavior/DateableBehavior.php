<?
# Date auto-conversion (y-m-d <=> m/d/y) on read/write.
class DateableBehavior extends ModelBehavior
{
	# XXX TODO
    	private $_settings = array();

	function setup(Model $model, $settings = array()) {
	        $default = array(
			'format'=>"M j, Y"
	        );
	
	        $this->_settings[$model->alias] = (!empty($settings)) ? $settings + $default : $default;
		if(!isset($this->_settings[$model->alias]['fields']))
		{
			throw new Exception("Missing fields information for date conversion of ".$model->alias);
		}
		if(!is_array($this->_settings[$model->alias]['fields'])) # One  only
		{
			$this->_settings[$model->alias]['fields'] = array($this->_settings[$model->alias]['fields']);
		}
	}

	function beforeSave(Model $model, $options = array())
	{
		$fields = $this->_settings[$model->alias]['fields'];
		foreach($fields as $field)
		{
			if(!empty($model->data[$model->alias][$field]))
			{
				$model->data[$model->alias][$field] =
					date("Y-m-d H:i:s", strtotime($model->data[$model->alias][$field]));
			} else if(isset($model->data[$model->alias][$field])) { # DOnt set blank date, may cause 1969.
				$model->data[$model->alias][$field] = null; # Blank entries get cleared. Works also if field already set, to clear
			}
		}

		return true;
	}

	function afterFind(Model $model, $results, $primary = false)
	{
		$fields = $this->_settings[$model->alias]['fields'];
		$format = $this->_settings[$model->alias]['format'];

		foreach($results as &$result)
		{

			foreach($fields as $field)
			{
				# ALSO protect against invalid dates 0000-00-00, etc.
				if(isset($result[$model->alias][$field]))
				{
					# All zeros
					if(preg_match("/^0*$/", preg_replace("/\D+/", "", $result[$model->alias][$field])))
					{
						#unset($result[$model->alias][$field]); # Causes  issues with rendering.
						$result[$model->alias][$field] = null; 
					} else {
						$result[$model->alias][$field] =
							date($format, strtotime($result[$model->alias][$field]));
					}

				}
			}

		}

		return $results;
	}

}
