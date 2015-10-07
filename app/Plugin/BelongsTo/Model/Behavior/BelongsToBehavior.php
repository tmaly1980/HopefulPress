<?
class BelongsToBehavior extends ModelBehavior
{
        function beforeFind(Model $model, $query) # Filter out blank names.
        {
                if(empty($query['conditions'])) { $query['conditions'] = array(); }
                if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); }
                $query['conditions'][] = "{$model->alias}.{$model->displayField} != '' AND {$model->alias}.{$model->displayField} IS NOT NULL ";
                return $query;
        }

}
