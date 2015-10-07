<?
class Link extends AppModel
{ 
	var $actsAs = array('Sortable.Sortable'); 
	var $belongsTo = array(
		'LinkCategory'=>array(
			'foreignKey'=>'link_category_id'
		),
	);

}
