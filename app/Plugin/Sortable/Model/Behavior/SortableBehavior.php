<?
class SortableBehavior extends ModelBehavior
{
	function beforeFind(Model $model, $query)
	{
		if(!isset($query['order'])) { $query['order'] = array(); }
		if(!is_array($query['order'])) { $query['order'] = array($query['order']); }

                if($model->hasField("ix")) # Preferred name of field.
                {
                        $query['order'][] = "{$model->alias}.ix IS NULL, {$model->alias}.ix ASC"; # Always last resort, or first, if nothing else set.
		}

		return $query;
	}

}
