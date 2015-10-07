<?
class ABResult extends AppModel
{
	public $displayField = 'view';

	public $belongsTo = array(
		'MarketingVisit'=>array('foreignKey'=>'marketing_visit_id'),
	);

}
