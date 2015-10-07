<?

# Implements 'pinning' some records so they show first in a list.

class StickyBehavior extends ModelBehavior
{
	function stick($model, $id = null)
	{
		if(!empty($id)) { $model->id = $id; }
		$model->saveField("sticky", true);
	}
	function unstick($model, $id = null)
	{
		if(!empty($id)) { $model->id = $id; }
		$model->saveField("sticky", false);
	}

	function beforeFind(Model $model, $query)
	{ # Must return query to continue, else will halt.
		if(!$model->hasField("sticky")) { return $query; }

		$order = !empty($query['order']) ? $query['order'] : array();
		if(is_string($order) && !empty($order)) { $order = array($order); }

		$neworder = array(
			"{$model->alias}.sticky DESC"
		);

		$query['order'] = array_merge($neworder, $order);
		# Needs to go first.

		return $query;
	}


}
