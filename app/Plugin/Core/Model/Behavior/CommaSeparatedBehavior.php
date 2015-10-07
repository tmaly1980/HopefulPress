<?
class CommaSeparatedBehavior extends ModelBehavior
{
    # We NEED explicit  fields, so we fix upon find(), for forms.
    # XXX TODO
    # NEVERMIND FOR NOW

    # SAVE SET ARRAY DATA AS COMMA SEPARATED LIST.
    function beforeSave(Model $model, $options=array())
    {
    	foreach($model->data[$model->alias] as $k=>$v)
	{
		if(is_array($v))
		{
			$model->data[$model->alias][$k] = join(",", $v);
		}
	}
    	
    	return true;
    }
}
