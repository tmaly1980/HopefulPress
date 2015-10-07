<?
class ShareStatBehavior extends ModelBehavior
{
	function shareCounts($model, $from = 7, $to = "NOW()")
	{
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		$shares = $model->find('all', array(
			'conditions'=>array("DATE({$model->alias}.created) BETWEEN $from AND $to"),
			'fields'=>array('COUNT(*) as count', "{$model->alias}.*"), 
			'recursive'=>-1,
			'group'=>'page_url','order'=>'count DESC'
		));

		return $shares;
	}

}
