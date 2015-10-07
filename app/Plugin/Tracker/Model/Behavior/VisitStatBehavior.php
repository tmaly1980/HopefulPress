<?
class VisitStatBehavior extends ModelBehavior
{
	function popular($model, $from = 7, $to = "NOW()") # For past week.
	{
		if($from === true) { $from = 7; }
		if($to === true) { $to = "NOW()"; }

		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		# Query page views.... titles are retrieved via tracker's shutdown() call after render()

		$visits = $model->find('all', array(
			'conditions'=>array("start BETWEEN $from AND $to AND start != end"),
			'fields'=>array('(UNIX_TIMESTAMP(end) - UNIX_TIMESTAMP(start)) AS time', "{$model->alias}.*"), 
			'recursive'=>-1,
			#'group'=>'session_id',
			'order'=>'time DESC'
		));

		return $visits;
	}
	function visitList($model, $from, $to = "NOW()")
	{ # List of visits by date
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		} 
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		$visits = $model->find('all', array(
			'recursive'=>-1,
			'fields'=>array("DATE({$model->alias}.start) AS date", 'COUNT(*) AS count'),
			'conditions'=>array(
				"{$model->alias}.start BETWEEN $from AND $to"
			),
			'group'=>"date",
		));
		$visitList = Set::combine($visits, "{n}.0.date", "{n}.0.count");
		return $visitList;
	}

	function repeatVisitList($model, $from, $to = "NOW()")
	{ # List of visits by date
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);
		$visits = $model->find('all', array(
			'recursive'=>-1,
			'fields'=>array("DATE({$model->alias}.start) AS date", "COUNT(DISTINCT {$model->alias}.id) AS count"),
			'conditions'=>array(
				"{$model->alias}.start BETWEEN $from AND $to",
				'OtherVisit.session_id IS NOT NULL'
			),
			'group'=>"date",
			'joins'=>array(array(
				'table'=>$model->useTable, 'alias'=>'OtherVisit', 'type'=>'LEFT',
				'conditions'=>array("{$model->alias}.session_id = OtherVisit.session_id AND {$model->alias}.id != OtherVisit.id")
			)),
		));
		$visitList = Set::combine($visits, "{n}.0.date", "{n}.0.count");
		return $visitList;
	}

	function newVisitList($model, $from, $to = "NOW()")
	{ # List of visits by date
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);
		$visits = $model->find('all', array(
			'recursive'=>-1,
			'fields'=>array("DATE({$model->alias}.start) AS date", "COUNT(DISTINCT {$model->alias}.id) AS count"),
			'conditions'=>array(
				"{$model->alias}.start BETWEEN $from AND $to",
				'OtherVisit.session_id IS NULL'
			),
			'group'=>"date",
			'joins'=>array(array(
				'table'=>$model->useTable, 'alias'=>'OtherVisit', 'type'=>'LEFT',
				'conditions'=>array("{$model->alias}.session_id = OtherVisit.session_id AND {$model->alias}.id != OtherVisit.id")
			)),
		));
		$visitList = Set::combine($visits, "{n}.0.date", "{n}.0.count");
		return $visitList;
	}

	function allVisitData($model, $range, $dateformat = 'Y-m-d') # Get full lists of total,new,repeat, and fill in gaps
	{
		$visitList = $model->visitList($range);
		$new_visitList = $model->newVisitList($range);
		$repeat_visitList = $model->repeatVisitList($range);

		#print_r($visitList);

		# Fill in gaps.

		# Figure out what the oldest date is, set that as first in set.
		$mindate = (!empty($visitList) || !empty($new_visitList) || !empty($repeat_visitList)) ? min(array_merge(array_keys($visitList),array_keys($new_visitList),array_keys($repeat_visitList))) : date('Y-m-d');
		$daysago = ceil((time() - strtotime($mindate))/(60*60*24));

		#error_log("daysago=$daysago");

		# Get date range (90 days)
		$visits = array();
		for ($d = $daysago; $d>=0; $d--) # Always do one more day than needed, so we can at least plot a line if there is just one day
		{
			$date = date("Y-m-d", strtotime("$d days ago"));
			$key = date($dateformat, strtotime("$d days ago"));
			#error_log("D=$date");
			$visits['total'][$key] = !empty($visitList[$date]) ? $visitList[$date] : 0;
			$visits['new'][$key] = !empty($new_visitList[$date]) ? $new_visitList[$date] : 0;
			$visits['repeat'][$key] = !empty($repeat_visitList[$date]) ? $repeat_visitList[$date] : 0;
		}

		return $visits;
	}

	function visitQueries($model)
	{
		$d = $model->dates();
		$visitQueries = $model->visitQueries = array(
			'today'=>"DATE({$model->alias}.start) = DATE(NOW())",
			'yesterday'=>"DATE({$model->alias}.start) = DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)",

			'thisweek'=>"DATE({$model->alias}.start) BETWEEN '{$d['thissunday']}' AND '{$d['thissaturday']}'",
			'lastweek'=>"DATE({$model->alias}.start) BETWEEN '{$d['lastsunday']}' AND '{$d['lastsaturday']}'",

			'thismonth'=>"DATE({$model->alias}.start) BETWEEN '{$d['thismonthstart']}' AND '{$d['thismonthend']}'",
			'lastmonth'=>"DATE({$model->alias}.start) BETWEEN '{$d['lastmonthstart']}' AND '{$d['lastmonthend']}'",
		);
		return $visitQueries;
	}

	function visitCounts($model)
	{
		$d = $model->dates();
		$visitQueries = $model->visitQueries();

		$visits = array();

		$model->recursive = -1;
		foreach($visitQueries as $key=>$q)
		{
			$visits[$key] = $model->count($q);
		}

		# ALSO store new visitors this week and repeat visitors this week
		$visits['newthisweek'] = $model->find('count', array(
			'fields'=>array("COUNT(DISTINCT {$model->alias}.id) AS count"),
			'conditions'=>array($visitQueries['thisweek'],'OtherVisit.session_id IS NULL'),
			'joins'=>array(
				array(
				'type'=>'LEFT','table'=>$model->useTable, 'alias'=>'OtherVisit', 
				'conditions'=>array("{$model->alias}.session_id = OtherVisit.session_id AND {$model->alias}.id != OtherVisit.id")
				)
			),
		));

		$visits['repeatthisweek'] = $model->find('count', array(
			'fields'=>array("COUNT(DISTINCT {$model->alias}.id) AS count"),
			'conditions'=>array($visitQueries['thisweek'],'OtherVisit.session_id IS NOT NULL'),
			'joins'=>array(array(
				'table'=>$model->useTable, 'alias'=>'OtherVisit', 'type'=>'LEFT',
				'conditions'=>array("{$model->alias}.session_id = OtherVisit.session_id AND {$model->alias}.id != OtherVisit.id")
			)),
		));

		$visits['uniquethisweek'] = $model->find('count', array(
			'fields'=>array("COUNT(DISTINCT {$model->alias}.session_id) AS count"),
			'conditions'=>array($visitQueries['thisweek']),
		));


		# TODO maybe
		# Get the # of visits for repeats:
		# SELECT COUNT(*),SiteVisit.session_id FROM `wpw`.`site_visits` AS `SiteVisit` WHERE DATE(`SiteVisit`.`start`) BETWEEN '2012-12-02' AND '2012-12-08 23:59:59' GROUP BY `SiteVisit`.`session_id` having count(1)>1;


		# Data should be based on days already in week, and comparison against last week should be based on same # of days... (so not alwasys saying worse, early on)
		# So store hours, days, etc here so we can divide the 'previous' numbers later...
		$visits['thishours'] = intval(date('H'));
		if(empty($visits['thishours'])) { $visits['thishours'] = 1; }
		$visits['thisdays'] = date('d');
		$visits['lastmonthdays'] = date('d', strtotime($d['lastmonthend'])); # May differ than this month's days, so we need ot properly divide and not get skewed nums

		return $visits;
	}

	var $durations = array(
			60=>"< 1 min",
			300=>"1 - 5 mins",
			600=>"5 - 10 mins",
			1800=>"10 - 30 mins",
			3600=>"30 - 60 mins",
			86400=>"> 1 hour", 
			# Exclude ones on more than a day, those are bogus
	);

	function durations($model)
	{
		return $this->durations;
	}

	function duration($model, $max)
	{
		return $this->durations[$max];
	}

	function duration_min($model, $max)
	{
		$min = 0;
		foreach($this->durations as $durmax=>$timestring)
		{
			if($max == $durmax)
			{
				return $min;
			}
			$min = $durmax;
		}
		return $min;
	}
		

	function durationList($model, $from = 7, $to = "NOW()", $raw = false)
	{
		if($from === true) { $from = 7; }
		if($to === true) { $to = 'NOW()'; }
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		$times = array();

		$min = 0;
		foreach($this->durations as $max=>$timestring)
		{
			$timecount = $model->find('count', array(
				'conditions'=>array(
					"start BETWEEN $from AND $to",
					"TIMESTAMPDIFF(SECOND,start,end) >= $min", # WAS created, modified
					"TIMESTAMPDIFF(SECOND,start,end) < $max"
					# Between forces <= and >=, may get dups
				)
			));

			$times[$raw?$max:$timestring] = $timecount;

			$min = $max; # move over.
		}

		return $times;
	}

	function referrers($model, $from = 7, $to = "NOW()")
	{
		if($from === true) { $from = 7; }
		if($to === true) { $to = "NOW()"; }
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		# Grouped by domain, or 'direct'

		$refs = $model->find('all', array(
			'fields'=>array('refdomain','COUNT(*) AS count'),
			'conditions'=>array("DATE(start) BETWEEN $from AND $to"),
			'group'=>'refdomain',
			'order'=>'count DESC',
			'limit'=>15 # LIMIT TO BEST
		));

		return Set::combine($refs, "{n}.{$model->alias}.refdomain", "{n}.0.count");
	}

	function locations($model, $from = 7, $to = "NOW()") # Count by geo location, country, state and city.
	{ # Depends on Geoip behavior and ip/ip_address fields, when being saved.
		if(is_numeric($from)) { # Days ago
			$from = "DATE_SUB($to, INTERVAL $from DAY)";
		}
		$from = $model->escapedate($from); $to = $model->escapedate($to);

		$locations = array();
		# group by locations.
		# MORE EFFICIENT... with 3 queries.

		$countries = $model->find('all', array(
			'fields'=>array('country', 'COUNT(*) AS count'),
			'conditions'=>array("DATE(start) BETWEEN $from AND $to"),
			'group'=>'country',
			'order'=>'count DESC'
			#'limit'=>5
		));

		$locations['countries'] = Set::combine($countries, "{n}.{$model->alias}.country", "{n}.0.count");
		$locations['countries'] = array();
		foreach($countries as $c)
		{
			$country = $c[$model->alias]['country'];
			$count = $c[0]['count'];

			$locations['countries'][$country] = $count;
		}

		$states = $model->find('all', array(
			'fields'=>array('country','state', 'COUNT(*) AS count'),
			'conditions'=>array("DATE(start) BETWEEN $from AND $to"),
			'group'=>'country, state',
			'order'=>'count DESC'
			#'limit'=>5
		));
		$locations['states'] = array();
		foreach($states as $s)
		{
			$country = $s[$model->alias]['country'];
			$state = $s[$model->alias]['state'];
			$count = $s[0]['count'];

			$locations['states'][$country][$state] = $count;
		}

		$cities = $model->find('all', array(
			'fields'=>array('country','state', 'city', 'COUNT(*) AS count'),
			'conditions'=>array("DATE(start) BETWEEN $from AND $to"),
			'group'=>'country, state, city',
			'order'=>'count DESC'
			#'limit'=>5
		));
		$locations['cities'] = array();
		foreach($cities as $c)
		{
			$country = $c[$model->alias]['country'];
			$state = $c[$model->alias]['state'];
			$city = $c[$model->alias]['city'];
			$count = $c[0]['count'];

			$locations['cities'][$country][$state][$city] = $count;
		}

		#print_r($locations);

		return $locations;
	}

}
