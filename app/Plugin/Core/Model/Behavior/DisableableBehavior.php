<?

class DisableableBehavior extends ModelBehavior
{
	# Don't show this entry unless we ask for 'all' (and in_admin) or 'first'

	function beforeFind(Model $model, $query)
	{
		if(Configure::read("in_admin"))  { return $query; }
		if(in_array($model->findQueryType, array('first'))) { return $query; }

		if(!is_array($query['conditions']))
		{
			$query['conditions'] = array();
		}
		if(isset($query['conditions']["{$model->alias}.disabled"]) || isset($query['conditions']["disabled"]) ||
			preg_grep("/{$model->alias}.disabled/", array_values($query['conditions'])) || 
			preg_grep("/disabled/", array_values($query['conditions']))  # Some other complex expression (like below)
		) {
			return $query; # already explicit.
		}

		# Need to accommodate either disabled IS NULL OR disabled = 0

		#$query['conditions']["{$model->alias}.disabled"] = null; # Force not disabled.
		$query['conditions'][] = "({$model->alias}.disabled IS NULL OR {$model->alias}.disabled = 0)";

		return $query;
	}

}
