<?

# Mark related fields with user_id when status set.

class StatusLoggerBehavior extends ModelBehavior
{
	function beforeSave(Model $model, $options = array()) 
	{
		if(!empty($model->data[$model->alias]['status']))
		{
			$userField = Inflector::underscore($model->data[$model->alias]['status'])."_by"; # approved_by, adopted_by, etc.
			if($model->hasField($userField) && ($user_id = Configure::read("user_id")) && (!$model->id || !$model->field($userField))) # New record or doesn't have value already.
			{
				$model->data[$model->alias][$userField] = $user_id;
			}
		}
		return true;
	}
}
