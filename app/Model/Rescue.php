<?
class Rescue extends AppModel
{
	var  $displayField = 'title';

	var $belongsTo = array(
		'RescueLogo'=>array('foreignKey'=>'rescue_logo_id'),

	);

}
