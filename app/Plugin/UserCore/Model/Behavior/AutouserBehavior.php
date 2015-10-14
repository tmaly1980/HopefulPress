<?php

# Save user_id when available and possibly desired.
class AutouserBehavior extends ModelBehavior {

	private $_settings = array();
    	private $model = null;

    	function beforeFind(Model $model, $query = array()) # Allow find by idurl when ->id set.
    	{
    		return $query;
		# someday have self-filtering??
	}

    	function beforeSave(Model $model, $options = array()) {
		# *** only bother to set when record is being CREATED
		# don't want to wipe out previous owner just because
		# not mentioned in form fields...
		#error_log("autouser {$model->id}");

		# Only autocreate on new records.

		# Implement supporting member_id for members 
		# and not mixing up user_id with users


		if(!empty($model->autouser) && Configure::read("User.autouser") !== false)
		{
			if(AuthComponent::$sessionKey == 'Auth.Member')
			{
				$key = "member_id";
				$me = Configure::read("member_id");
			} else { # User.
				$key = "user_id";
				$me = Configure::read("user_id");
			}
			$me = Configure::read($key);

			#error_log("autouser READING $key=$me");

			#error_log("autouser DATA=".print_r($model->data[$model->alias],true));
			############error_log("SAVING {$model->alias}, AUTOUSER={$model->autouser}, ME+$me");

			if($me && $model->hasField($key))
			{
				# Set if new record or if value in form AND DB not set.
				if(!isset($model->data[$model->alias][$key]) && (!$model->id || !$model->field($key)))
				{
					$model->data[$model->alias][$key] = $me; # 
				
					#error_log("SETTING {$model->alias} $key = $me");
				}
			}
		}
        	return parent::beforeSave($model);
    	}
}
