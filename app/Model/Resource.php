<?
class Resource extends AppModel
{ 
	var $actsAs = array('Sortable.Sortable'); 
	var $belongsTo = array(
		'ResourceCategory'=>array(
			'foreignKey'=>'resource_category_id'
		),
	);

}
