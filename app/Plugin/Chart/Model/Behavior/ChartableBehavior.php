<?
# TODO: 
# y-axis whole numbers
# vertical grid lines

# grouping by month/etc should still show 0 for previous (unknown) month....
# XXX grouping should FIX hover labels (data returned should possibly pass/set some parameters??? (keep data- simpler?) 

# CLICK ON QUARTER SHOULD DRILLDOWN TO MONTHLY (point in center)
# CLICK ON MONTH DRILLDOWN TO DAILY

class ChartableBehavior extends ModelBehavior
{
	function defaultwhen($span) # year, quarter, month, week
	{
		if($span == 'year') { return date('Y'); }
		if($span == 'quarter') { return date('Y')." Q".ceil(date('m')/3); }
		if($span == 'month') { return date('Y-m'); }
		if($span == 'week') { return date('Y \WW'); }
		if($span == 'day') { return date('Y-m-d'); }
		return null;
	}

	/* 
	2013: list by month (but click to quarter)
	2014 Q3: list by week (4 x 3)
	2014-05: list by day (but click to week)
	*/

	function whenend($span, $when) # Assumes proper formatted when
	{
		if($span == 'year') { 
			return sprintf("%u-12-31", $when);
		} else if($span == 'quarter') { 
			list($year, $quarter) = split(" Q", $when);
			$n = $quarter*3; # 4 => +12 mo - 1 day
			error_log("SPAN=$span, Y=$year, Q=$quarter, +N=$n");
			return date('Y-m-d', strtotime("$year-01-01 +{$n} month -1 day"));
		} else if($span == 'month') { 
			list($year, $month) = split("-", $when);
			return date('Y-m-d', strtotime("$year-$month-01 +1 month -1 day"));
		} else if($span == 'week') { 
			list($year, $week) = split(" W", $when);
			$lastweek = $week-1;
			return date('Y-m-d', strtotime("next saturday", strtotime("$year-01-01 +{$lastweek} week -1 day")));
		}
	}

	function whenstart($span, $when)
	{
		if($span == 'year') { 
			return sprintf("%u-01-01", $when);
		} else if($span == 'quarter') { 
			list($year, $quarter) = split(" Q", $when);
			$n = $quarter*3-2; # q end - 2 months
				# 1 => 1, 2 => 4, 3=>7, 4=>10
			return date('Y-m-d', strtotime("$year-01-01 +{$n} month -1 month"));
		} else if($span == 'month') { 
			list($year, $month) = split("-", $when);
			return sprintf("%4u-%02u-01", $year, $month);
		} else if($span == 'week') { 
			list($year, $week) = split(" W", $when);
			$lastweek = $week-1;
			return date('Y-m-d', strtotime("last sunday", strtotime("$year-01-01 +{$lastweek} week +1 day")));
		}
	}

	function plotdates_between($model, $params)
	{
		$span = !empty($params['datespan']) ? $params['datespan'] : 'quarter';
		$default = $this->defaultwhen($span);
		if($span == 'year')
		{
			$when = !empty($params['year']) ? $params['year'] : $default;
		} else if ($span == 'quarter') {
			$when = !empty($params['quarter']) ? $params['quarter'] : $default;
		} else if ($span == 'month') {
			$when = !empty($params['month']) ? $params['month'] : $default;
		} else if ($span == 'week') { 
			$when = !empty($params['week']) ? $params['week'] : $default;
		} else if ($span == 'day') { 
			$when = !empty($params['day']) ? $params['day'] : $default;
		}

		$end = $this->whenend($span, $when);
		$start = $this->whenstart($span, $when);
		return array($start,$end);
	}

	# Fills in dates without data, and groups values to keep data points uncluttered
	function plotdates($model, $x, $y, $params, $dataonly = false)
	{
		#$end = !empty($params['end']) ? $params['end'] : date('Y-m-d'); 
		#$start = !empty($params['start']) ? $params['start'] : date('Y-01-01', strtotime($end)); 

		$span = !empty($params['datespan']) ? $params['datespan'] : 'quarter';
		# Span will group result values/data points

		if(!empty($params['start']))
		{
			$start = $params['start'];
			$end = !empty($params['end']) ? $params['end'] : date('Y-m-d'); # Today is default end, if start is provided.
		} else {
			list($start,$end) = $model->plotdates_between($params);
			# Will limit to within the last 'span', ie THIS month (not 30 days)
		}

		#echo "FAULT HERE: FOR $span, FROM $when, START=$start, END=$end\n";

		$conditions = !empty($params['conditions']) ? $params['conditions'] : array();
		# ALLOW FOR PARAMS SPECIFIED IN MODEL, ie filtering by block/field/etc

		$conditions[] = "$x BETWEEN '$start' AND '$end'";
		unset($params['conditions']); # Dont overwrite.

		$data = $model->plotdata(
			array($x,$y),
			$conditions,
			$x,
			$params # May be recursive, etc...
		);

		#echo "RAW=".print_r($data,true);

		# Still kept as dates, since we need to parse and fill in gaps.
		$grouped = $model->groupdatedata($data, $span);

		#echo "GR=".print_r($grouped,true);

		# filling dates should be aware of interval
		$filled = $model->plotfilldates($grouped, $span, $start, $end);

		#echo "FIL=".print_r($filled,true);

		# Converting dates to proper interval formats, ie 2012 Q3
		#####$formatted = $model->plotformatdates($filled, $span);
		# XXX T0ODO BROKEN
		$sorted = $model->sortdates($filled);
		$formatted = $sorted;

		#echo "FORM=".print_r($formatted,true);

		#echo "T=".print_r($formatted,true)."\n";

		$complete = $formatted;

		error_log("DATES=".print_r($complete,true));

		# Customize params to make sense for this.
		$newopts = $this->plotopts($data, $span);

		return $dataonly ? $complete : array(
			'data'=>$complete,
			'opts'=>$newopts
		);
	}

	function sortdates($model, $data)
	{
		$sorted = $data;
		ksort($sorted);
		return $sorted;
	}

	/* ODD: 
	q4 includes september (4 months)
	q1 skips january
	q2 misses part of april
	*/


	function plotopts($data, $interval)
	{
		$params = array();

		if($interval == '3 months')
		{
			$params['xLabels'] = "months"; # Only bother showing 4 months
			$params['parseTime'] = false; # Keep literal....
			$params['xdateformat'] = 'M Y'; // maybe?
		} else if ($interval == '1 month') { 
			$params['xLabels'] = "week";
			$params['pointdateformat'] = 'Y week W';
			$params['xdateformat'] = 'M d Y'; // maybe?
		} else if ($interval == '1 week') { 
			$params['xLabels'] = "day";
			$params['pointdateformat'] = 'M j Y';
			$params['xdateformat'] = 'M d Y'; // maybe?
		}


		return $params;
	}

	function spanformat($date, $span) # Smallest unit inside the span
	{ # *** HUMAN READABLE ***
		$time = strtotime($date);
		list($month,$day,$year) = split("/", date("m/d/Y", $time));

		if($span == 'year') # To quarter
		{
			$quarter = ceil($month/3);
			#return sprintf("%4u Q%u", $year, $quarter);
			return date("F Y", strtotime($date));
		} else if ($span == 'quarter') { # To week
			$sunday = date("M j", strtotime("last sunday", strtotime("$date +1 day")));
			$saturday = date("M j", strtotime("next saturday", strtotime("$date -1 day")));
			return "$sunday - $saturday";
		} else if ($span == 'month') { # To day
			return date("D M j", $time);
		} else if ($span == 'week') { # To day
			return date("D M j", $time);
		} else if ($span == 'day') { # To hour
			return date("H", $time);
		}
	}

	function dateround($time, $span) # y-m-d in smallest unit for span
	{ # Clustering points near each other as the same.
		list($month,$day,$year) = split("/", date("m/d/Y", $time));

		$date = date('Y-m-d', $time);

		if($span == 'year') # Round to quarter
		{
			$quarter = ceil($month/3);
				# 1-3 => 1, 4-6 => 4, 7-9 => 7, 10-12 => 10
				# 2/3 = q1, 4/3 = q2
				# q*3 - 2 =>
				# 1 => 1, 2 => 4, 3 => 7, 4 => 10
			$qmonth = $quarter*3 - 2;
			#return sprintf("%4u-%02u-01", $year,$qmonth);
			# MONTHLY
			return date("Y-m-01", $time);
		} else if ($span == 'quarter') { # Round to week
			return date("Y-m-d", strtotime("last sunday", strtotime("$date +1 day")));
		} else if ($span == 'month') { # Round to day
			return date("Y-m-d", $time);
		} else if ($span == 'week') { # Round to day
			return date("Y-m-d", $time);
		} else if ($span == 'day') { # Round to hour
			return date("H", $time);
		}
	}

	function groupdatedata($model, $data, $span) # Cluster close points together.
	{
		$out = array();

		foreach($data as $date=>$values)
		{
			$dateout = $this->dateround(strtotime($date), $span);
			if(empty($out[$dateout])) { $out[$dateout] = 0; }
			$out[$dateout] += $values; 
		}

		return $out;
	}

	function plotformatdates($model, $data, $span)
	{ # Reformat in sensible unit/size
		$out = array();
		foreach($data as $date=>$values)
		{
			$dateout = $this->spanformat($date, $span);
			error_log("D=($date)=$dateout");
			$out[$dateout] = $values; 
		}

		return $out;

	}

	function plotfilldates($model, $data, $span, $start, $end) # So line graphs jump back down....
	{
		if(empty($data)) { return $data; } // NONE

		$ymddata = array();

		#echo "START=$start, END=$end";

		$intervalsecmap = array( # Smallest unit
			'hour'=>60*60,
			'day'=>60*60*24,
			'week'=>60*60*24, # day
			'month'=>60*60*24, # day
			'quarter'=>60*60*24*365/52, # week
			'year'=>60*60*24*365/12, # month
		);
		$intervalsecs = $intervalsecmap[$span];

		# Fill in all dates.

		# NEVER do dates into the future.
		for($time = strtotime($start); $time <= strtotime($end) && $time <= time(); $time += $intervalsecs)
		{
			$date = $this->dateround($time, $span);
			$ymddata[$date] = 0;
		}

		# Go thru data points and populate rounded date unit.

		foreach($data as $date=>$values)
		{
			# Now "round" date to sensible unit (might not be a day)
			$rounded = $this->dateround(strtotime($date), $span);
			$ymddata[$rounded] = $values;
		}

		return $ymddata;
	}

	function plotdata($model, $fields, $cond = array(), $group = null, $other_params = array()) # Find formatter
	{
		# Aggregate fields NEED to be put into virtual fields so 'list' works

		$opts = array(
			'fields'=>$fields,
			'conditions'=>$cond,
			'group'=>$group
		); # limit, etc can be passed as last parameter
		$params = array_merge($opts, $other_params);
		#print_r($params);
		$data = $model->find('list', $params);
		# LIST fails to do joins... (ex: ip blacklisting)

		return $data;
	}

	function chart_dates($model, $query)
	{
		$method = "dateslist_$query";
		if(method_exists($model, $method))
		{
			return $model->$method();
		}
		return null;
	}

	function plot($model, $query, $params = array())
	{

		$data = array();

		$method = "plot_$query";
		if(method_exists($model, $method))
		{
			if(!empty($params['compare'])) # Allow multiple datasets, underlying function handles each separately
			{
				$series = split(",", $params['compare']);
				foreach($series as $sery)
				{
					$paramsonce = $params;
					$paramsonce['series'] = $sery;
					# Data is in {x=>y, x2=>y2, x3=>y3} format... need to convert to [ [x,y]. [x,y], [x,y] ]
					$data[$sery] = $model->$method($params);
				}
				return $model->$method($params);
			} else {
				$data = $model->$method($params);
			}
		}

		#$model->sqlDump();

		return $data;


		####################
		#
		# SAMPLE DATA
		$data = array(
			'Series 1'=> array(
				array(1,5),
				array(2,5),
				array(3,5),
				array(4,5),
			),
			'Series 2'=> array(
				array(1,2),
				array(2,3),
				array(3,4),
				array(5,5),

			),
			'Series 3'=>array(
				array(1,2),
				array(2,2),
				array(3,1),
				array(4,6),
			),

		);

		return $data;
	}
}
