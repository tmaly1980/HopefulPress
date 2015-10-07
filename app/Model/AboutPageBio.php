<?
class AboutPageBio extends AppModel
{
	var $actsAs = array('Sortable.Sortable');

	var $belongsTo = array(
		'PagePhoto'=>array(
			'foreignKey'=>'page_photo_id'
		)
	);

}
