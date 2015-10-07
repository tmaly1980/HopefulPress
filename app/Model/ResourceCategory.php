<?
class ResourceCategory extends AppModel
{
	var $actsAs = array('Sortable.Sortable'); 
	var $hasMany = array(
		'Resource'=>array(
			'foreignKey'=>'resource_category_id'
		),
	);

}
