<?
class LinkCategory extends AppModel
{
	var $actsAs = array('Sortable.Sortable'); 
	var $hasMany = array(
		'Link'=>array(
			'foreignKey'=>'link_category_id'
		),
	);

}
