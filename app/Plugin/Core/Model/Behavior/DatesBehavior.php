<?
class DatesBehavior extends ModelBehavior
{
	function dateslist($model, $field) # Applicable years and ALL quarters, months, weeks
	{
		$model->virtualFields["year_$field"] = "YEAR({$model->alias}.$field)";

		# Should get list of ALL options, so dropdown can switch between sets.
		$whenlist = array(
			'year'=>array(),
			'quarter'=>array(),
			'month'=>array(),
			'week'=>array()
		);

		$whenlist['year'] = $model->find('list', array('fields'=>array("year_$field", "year_$field"), array('group'=>"year_$field",'order'=>"year_$field DESC")));

		# Get FULL list of quarters, months, weeks for available years...

		$weeks = array();
		foreach($whenlist['year'] as $year)
		{
			# Quarters.
			for($i = 4; $i >= 1; $i--) { $whenlist['quarter']["$year Q$i"] = "Q$i $year"; }

			# Months
			for($i = 12; $i >= 1; $i--) { 
				$ym = sprintf("%4u-%02u", $year, $i);
				$my = date("M Y", strtotime("$ym-01"));
				$whenlist['month'][$ym] = $my;
			}

			# Weeks
			for($i = 52; $i >= 1; $i--) { 
				$date = date("Y-m-d", strtotime("$year-01-01 +$i week"));

				$weekstart = $this->weekstart($date);
				$weekend = $this->weekend($date);

				$weekname = sprintf("%s - %s",
					$weekstart, $weekend
				);
				# **** pretty span, ie Jan 31 - Feb 6 2014
				$week = date('o \WW', strtotime($date)); # Glitch with 12-31 and year, gives wrong year with 'Y', needs to use 'o'
				$weeks[$week] = $weekname;
			}
		}
		$whenlist['week'] = $weeks;

		return $whenlist;
	}

	function weekstart($date)
	{
		return date('M j', strtotime('last sunday', strtotime("$date +1 day")));
	}

	function weekend($date)
	{
		return date('M j Y', strtotime('next saturday', strtotime("$date -1 day")));
	}


}
