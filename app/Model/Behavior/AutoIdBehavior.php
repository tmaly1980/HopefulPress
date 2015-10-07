<?
# Filter by field_id, site_id, etc if set.
class AutoIdBehavior extends ModelBehavior
{
	var $_defaults  = array(
		'fields' => array('site_id'), # Can be multiple....
		'bypassField'=>array('manager'=>1),
	);

  	public function setup(Model $Model, $config = array()) {
                if (isset($config[0])) {
                        $config['type'] = $config[0];
                        unset($config[0]);
                }
                $settings = $config + $this->_defaults;

		if(!is_array($settings['fields'])) { $settings['fields'] = array($settings['fields']); }

                $this->settings[$Model->alias] = $settings;
        }


	function beforeFind(Model $model, $query = array()) # Restrict to current rescue.
	{
		foreach($this->settings[$model->alias]['fields'] as $field)
		{
			$selfClass = Inflector::classify(preg_replace("/_id$/", "", $field)); # site_id => Site
			$field_id = Configure::read($field);
	
			if(!$model->hasField($field) || empty($field_id) || (isset($model->autoid) && $model->autoid === false) || (isset($query['autoid']) && $query['autoid'] === false)) 
			{ 
				return $query; 
			}
			# Can disable by setting $model->autoid = false (even when field_id there)
	
			# If self, convert to proper id.
			if($model->alias == $selfClass) { $model->id = $field_id; }
	
			if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); } # Fix so check below works.
	
			if(isset($query['conditions'][$field]) || isset($query['conditions'][$model->alias.".$field"])) { 
				# Ignore (allow any) if field_id is set to false
				if(isset($query['conditions'][$field]) && $query['conditions'][$field] === false)
				{
					unset($query['conditions'][$field]);
				}
				if(isset($query['conditions'][$model->alias.".$field"])) 
				{
					unset($query['conditions'][$model->alias.".$field"]);
				}
				return $query;
			}
	
			if(!empty($model->bypassField)) # Intersite potential...(mostly for manager users)
			{
				$query['conditions']['OR'] = array(
					$model->alias.".$field" => $field_id,
					$model->alias.".".$model->bypassField => 1 # ie manager=>1
				);
			} else {
				$query['conditions'][$model->alias.".$field"] = $field_id;
			}

		}

		return $query;
	}

	function beforeSave(Model $model, $options = array())
	{ # Problem with filtering out manager user accounts is that manager flag probably won't be passed

		foreach($this->settings[$model->alias]['fields'] as $field)
		{
			$selfClass = Inflector::classify(preg_replace("/_id$/", "", $field)); # site_id => Site
			$field_id = Configure::read($field);

			if(!$model->hasField($field) || empty($field_id) || (isset($model->autoid) && $model->autoid === false) || (isset($query['autoid']) && $query['autoid'] === false)) { 
				return true;
			}
	
			if(isset($model->data[$model->alias][$field])) { return true; }
	
			$model->data[$model->alias][$field] = $field_id;
	
			if(!empty($model->whitelist)) # Otherwise no restrictions.
			{
				$model->whitelist[] = $field; # Add field_id so works with saveField()
			}

		}
		return true;
	}

}
