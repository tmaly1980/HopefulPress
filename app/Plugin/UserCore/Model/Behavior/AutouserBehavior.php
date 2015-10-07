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
		if(!empty($model->id)) { return true; } # SKIP! existing record.
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
			}
			$me = Configure::read($key);

			if($me && $model->hasField($key) && !isset($model->data[$model->alias]['member_id']) && !isset($model->data[$model->alias]['user_id']))
			# needs to not reset if one or other set.
			{
				$model->data[$model->alias][$key] = $me;
			}
		}
        	return parent::beforeSave($model);
    	}
}
