<?
class PageViewStatBehavior extends ModelBehavior
{
	function total($model, $from, $to = 'NOW()')
	{
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);
		return $model->find('count', array('recursive'=>-1,
			'conditions'=>array("{$model->alias}.created BETWEEN $from AND $to")
		));
	}

	function popular($model, $from = 7, $to = "NOW()") # For past week.
	{
		if($from === true) { $from = 7; }
		if($to === true) { $to = "NOW()"; }

		if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $from)) { 
			# from is already a date.... grab stuff just for that date
			$to = "$from 23:59:59";
			$from = "$from 00:00:00";
		} else if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		# Query page views.... titles are retrieved via tracker's shutdown() call after render()

		$pages = $model->find('all', array(
			'conditions'=>array("{$model->alias}.created BETWEEN $from AND $to"),
			'fields'=>array('COUNT(*) as count', "{$model->alias}.*"), 
			'recursive'=>0,#-1,#  0 needed for blacklisting stupid IPs
			'group'=>"{$model->alias}.url",'order'=>"count DESC"
		));

		return $pages;
	}

	function get_page_title($model, $page) # May include object name.
	{
		$url = $page[$model->alias]['url'];
		# Try to parse url....
		$parsed = Router::parse($url);

		$title = $url; # Default

		#error_log("GET_TITLE URL=$url, PARSE=".print_r($parsed,true));

		$section = Inflector::humanize($parsed['controller']);

		# GENERALIZE FOR sites without db content....

		if($parsed['action'] == 'index')
		{
			$title = $section;
		} else if ($parsed['action'] == 'view') {
			# Maybe fix what param name is...

			$contentModel = Inflector::singularize(Inflector::camelize($parsed['controller']));
			$model->loadModel($contentModel);
			if($model->{$contentModel}->Behaviors->attached("Singleton"))
			{
				$title = Inflector::singularize($section);
			} else if(!empty($parsed['pass'][0])) {
				$id = $parsed['pass'][0];

				$titleField = $model->{$contentModel}->displayField;
				$idField = !is_numeric($id) && $model->{$contentModel}->hasField("url") ? "url" : $model->{$contentModel}->primaryKey;
				$titleValue = $model->{$contentModel}->field($titleField, array($idField=>$id));

				$title = "$section &raquo; $titleValue";
			}
		} # else use url as default.

		return $title;
	}
}
