<?

class MembersOnlyBehavior extends ModelBehavior
{
	function beforeSave(Model $model, $options = array())
	{
		if(!$model->hasField("members_only")) { return true; }

		if(Configure::read("members_only")) { 
			$model->data[$model->alias]['members_only'] = 1;
		} else {
			$model->data[$model->alias]['members_only'] = 0;
		}
		return true;
	}

	function beforeFind(Model $model, $query)
	{ # Must return query to continue, else will halt.
		
		if(!$model->hasField("members_only")) { return $query; }

		#$model->setBelongsTo("MembersPage",array('className'=>'MembersPage')); # If needed.

		if(isset($query['conditions']["{$model->alias}.members_only"]) || isset($query['conditions']["members_only"]))
		{
			return $query; # Leave alone, explicit.
		}

		if(Configure::read("members_only")) { 
			$query['conditions']["{$model->alias}.members_only"] = 1;
		} else {
			$query['conditions']["{$model->alias}.members_only"] = 0;
		}

		return $query;
	}
}
