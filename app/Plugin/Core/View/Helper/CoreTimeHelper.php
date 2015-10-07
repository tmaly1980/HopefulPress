<?
App::uses('TimeHelper','View/Helper');
class CoreTimeHelper extends TimeHelper 
{
	# TimeHelper overrides default __get(), so it cannot load any helpers for itself.
	# Any reference to other helpers  must go via  $this->_View->HELPER

	function date($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return $this->format($date, "%A, %B %e, %Y");
	}

	function now() # For passing a default date to many things below.
	{
		return date("Y-m-d H:i:s");
	}

	function sameday($date1, $date2 = null) # OK to say 'immediately' 
	{
		if(empty($date2)) { $date2 = date('Y-m-d'); }
		return (date("Y-m-d", strtotime($date1)) == date("Y-m-d", strtotime($date2)));
	}

	function sametime($d1,$d2)
	{
		return date("H:i", strtotime($d1)) == date("H:i", strtotime($d2));
	}

	function md($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if(date("Y") == date("Y", strtotime($date)))
		{
			return date("m/d", strtotime($date));
		} else {
			return date("m/d/Y", strtotime($date));
		}
	}

	function mdy($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("m/d/Y", strtotime($date));
	}

	function time_24hm($date = null) # Default without meridian.
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("H:i", strtotime($date));
	}
	function time_12hm($date = null) # Default with meridian.
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("g:ia", strtotime($date));
	}
	function time_hm($date = null) # Default with meridian.
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("g:ia", strtotime($date));
	}

	function hms($date = null) # Default with meridian.
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("g:i:sa", strtotime($date));
	}
	function hm($date = null) # Default with meridian.
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("g:ia", strtotime($date));
	}

	function monthdyear($date = null) { return $this->monthdy($date, false); }
	function monthdy($date = null, $short = true)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return date("F jS", strtotime($date));
		} else {
			return date("F jS, Y", strtotime($date));
		}
	}

	function secs2date($secs)
	{
		return date("Y-m-d H:i:s", $secs);
	}

	function monthname($num) # From 1=>January, 12=>December
	{
		return date("F", strtotime(sprintf("2012-%02u-01", $num)));
	}
	function monname($num) # From 1=>Jan, 12=>Dec
	{
		return date("M", strtotime(sprintf("2012-%02u-01", $num)));
	}

	function mony($date = null, $short = true) # March 2011; January
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return date("F", strtotime($date));
		} else {
			return date("F Y", strtotime($date));
		}
	}

	function fulltime($date = null) { return $this->fulldate($date); }

	function fulldate($date) # Wednesday, March 31st, 2012
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		$time = strtotime($date);
		if(empty($date) || empty($time) || $time <= 0) { return; }
		return date("l, F j, Y, g:ia", $time);
	}

	function shorttime($date)
	{
		$time = strtotime($date);

		if(date("Y-m-d", strtotime($date)) == date("Y-m-d", time())) 
		{ # Same day
			return date("g:ia", $time);
		} else {
			if(date("Y", strtotime($date)) == date("Y", time())) 
			{ # Same year
				return date("D, M j, g:ia", $time);
			} else { 
				return date("D, M j, Y, g:ia", $time);

			}
		}
	}

	function mondyear($date = null) { 
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return $this->mondy($date, false); 
	}
	function mondy($date = null, $short = true)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return $this->mond($date);
		} else {
			return date("M j, Y", strtotime($date));
		}
	}
	function mond($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("M j", strtotime($date));
	}

	function monthy($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("F Y", strtotime($date));
	}
	function monthdyhm($date = null, $short = true)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return date("F j, g:ia", strtotime($date));
		} else {
			return date("F j, Y, g:ia", strtotime($date));
		}
	}

	function mondyhm($date = null, $short = true)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return date("M j, g:ia", strtotime($date));
		} else {
			return date("M j, Y, g:ia", strtotime($date));
		}
	}
	function mondyhms($date = null, $short = true)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		if($short && date("Y", strtotime($date)) == date("Y", time())) # Same year, no need for showing.
		{
			return date("M j, g:i:sa", strtotime($date));
		} else {
			return date("M j, Y, g:i:sa", strtotime($date));
		}
	}

	function mdyhms($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("m/d/Y g:i:sa", strtotime($date));
	}

	function mdyhm($date = null)
	{
		if(empty($date)) { $date = date('Y-m-d H:i:s'); }
		return date("m/d/Y g:ia", strtotime($date));
	}

	function yearweek_start($yearweek) # Gets date for sunday of.
	{
		if(!preg_match("/(\d{4})(\d{2})/", $yearweek, $match)) { return null; }
		array_shift($match);
		list($year, $week) = $match;

		$time = strtotime(sprintf("$year-01-01 +$week weeks -1 week"));

		return date("Y-m-d", strtotime(sprintf("-%d days", date("w", $time))));
		#return strtotime("$year-01-01 +$week weeks -1 week Sunday"); # subtract one since 01 means 1st.
	}
	function yearweek_end($yearweek) # Gets date for saturday of.
	{
		if(!preg_match("/(\d{4})(\d{2})/", $yearweek, $match)) { return null; }
		array_shift($match);
		list($year, $week) = $match;

		$time = strtotime(sprintf("$year-01-01 +$week weeks -1 week"));
		return date("Y-m-d", strtotime(sprintf("+%d days", 6 - date("w", $time))));

		#return strtotime("$year-01-01 +$week weeks -1 week next Saturday"); # subtract one since 01 means 1st.
	}

	function daysbetween($date1, $date2 = null) # Assumes 2nd is more recent, 1st is older.
	{
		if(empty($date2)) { $date2 = date("Y-m-d H:i:s"); }
		$secs = (strtotime($date2) - strtotime($date1));
		return floor($secs / (60*60*24) );
	}

	function decimal_between($date1, $date2)
	{
		$secs = abs(strtotime($date1) - strtotime($date2));
		$mins = $hours = $days = $weeks = $months = $years = 0;

		if($secs > 60)
		{
			$mins = $secs/60;
		}
		if($mins > 60)
		{
			$hours = $mins/60;
		}
		if($hours > 24)
		{
			$days = $hours/24;
		}
		if($days > 30)
		{
			$months = $days/30;
		}
		else if($days > 7)
		{
			$weeks = $days/7;
		}
		if($months > 12)
		{
			$years = $months/12;
		} else if ($weeks > 52) {
			$years = $weeks/52;
		}

		if(!empty($years)) { return sprintf("%0.1f years", $years); }
		if(!empty($months)) { return sprintf("%0.1f months", $months); }
		if(!empty($weeks)) { return sprintf("%0.1f weeks", $weeks); }
		if(!empty($days)) { return sprintf("%0.1f days", $days); }
		if(!empty($hours)) { return sprintf("%0.1f hours", $hours); }
		if(!empty($mins)) { return sprintf("%0.1f mins", $mins); }
		# Else, could be 0 secs
		return sprintf("%0.1f secs", $secs); 
	}

	function timebetween($date1, $date2 = null, $short = false)
	{
		if(empty($date1)) { return; }
		if(empty($date2)) { $date2 = date("Y-m-d H:i:s"); } # Default to vs. now
		$secs = abs(strtotime($date1) - strtotime($date2));

		return $this->timediff($secs, $short);
	}

	# If short is set to a number, only display that many time units (ie to 'month', w/o mention days)
	function timediff($secs, $short = false, $precision = 'minute') # Precise, to the minute.
	{
		$days = $hours = $minutes = $weeks = $months = $years = 0;

		$timeago  = array();
		if($secs >= 60)
		{
			$minutes = intval($secs/60);
			$secs = $secs % 60;
		}
		if($minutes >= 60)
		{
			$hours = intval($minutes/60);
			$minutes = $minutes % 60;
		}
		if($hours >= 24)
		{
			$days = intval($hours/24);
			$hours = $hours % 24;
		}
		if($days >= 7)
		{
			$weeks = intval($days/7);
			$days = $days % 7;
		}
		if($weeks >= 52/12)
		{
			$months = intval($weeks/(52/12));
			$weeks = $weeks % (52/12);
		}
		if($months > 12)
		{
			$years = intval($months/12);
			$months = $months % 12;
		}

		if($short)
		{
			$timeago = array();
			if($years>0) { $timeago[] = $years."y"; }
			if($months>0) { $timeago[] = $months."M"; }
			if($weeks>0) { $timeago[] = $weeks."w"; }
			if($days>0) { $timeago[] = $days."d"; }
			if($hours>0) { $timeago[] = $hours."h"; }
			if($minutes>0) { $timeago[] = $minutes."m"; }
			if($secs>0 && empty($timeago)) { $timeago[] = round($secs)."s"; }
			if (is_numeric($short) && $short >= 1) # cap off certain # of units.
			{
				return join(" ", array_slice($timeago,0,$short));
			}
			return join(" ", $timeago);
		}

		# For dates that are super long ago, show the date instead.
		# NO MORE!
		#if($months > 0) { $timeago[] = $months > 1 ? "$months months" : "$months month"; }
		if($years > 0) {
			$timeago[] = ($months > 0 ? "over ":"")."$years year".($years>1?"s":"");
		} 
		if($months > 0) { 
			$timeago[] = ($days > 0 ? "over ":"")."$months month".($months>1?"s":"");
		}
		if($weeks > 0) { 
			$timeago[] = $weeks > 1 ? "$weeks weeks" : "$weeks week"; 
		}
		if($days > 0) { 
			$timeago[] = $days > 1 ? "$days days" : "$days day"; 
		}
		if($hours > 0) { 
			$timeago[] = $hours > 1 ? "$hours hours" : "$hours hour"; 
		}
		if($minutes > 0) { 
			$timeago[] = $minutes > 1 ? "$minutes minutes" : "$minutes minute"; 
		}

		#if(empty($timeago)) { $timeago[] = "less than a minute"; }
		if(empty($timeago)) { $timeago[] = "just now"; } # Sounds nicer...

		# really only makes sense to show largest unit...(friendlier)
		return $timeago[0];
		#return join(" ", $timeago);
	}

	function smarttime($date, $long =false, $class = null) # Link of simple time with hover of exact time.
	{
		# Go around broken __get()
		return $this->_View->Html->link($this->timeago($date,$long), 'javascript:void(0)',
			array('class'=>"underline_hover $class",'title'=>$this->fulltime($date))
		);
	}

	function daysago($days)
	{
		$date = date("Y-m-d", strtotime("$days days ago"));
		return $this->timeago($date);
	}

	function age($date) # Assumes yyyy-mm-dd
	{
		return $this->timeAgoInWords($date,array('end'=>'100 year','relativeString'=>'%s old','accuracy'=>array('year'=>'month'))); 
	}

	function timeago($date, $long = false) # MUCH smarter, nicer to look at. 
	{
		if(empty($date)) { return "unknown"; }

		$time = strtotime($date);

		$secs = time() - $time;

		$days = $hours = $minutes = $weeks = $months = $years = 0;

		$minutes = intval($secs/60);
		$hours = intval($minutes/60);
		$days = intval($hours/24);
		$weeks = intval($days/7);
		$months = intval($days*12/365);
		$years = intval($days/365);

		$daynumofweek = date("w", $time);
		$thisdaynumofweek = date("w", time());

		if($secs <= 0)
		{
			return "just now";
		}
		else if($secs < 60)
		{
			return $secs > 1 ? "$secs seconds ago" : "$secs second ago";
		}
		else if($minutes < 45) # 24 minutes ago
		{
			return $minutes > 1 ? "$minutes minutes ago" : "$minutes minute ago";
		} else if ($minutes <= 75) { # 45 < x < 75, about an hour ago
			return "about an hour ago";
		} else if ($hours < 24) { # 7 hours ago
			return $hours > 1 ? "$hours hours ago" : "$hours hour ago";
		} else if ($days < 7 && $daynumofweek == $thisdaynumofweek-1) { # Yesterday
			return "Yesterday";
		} else if ($days < 7 && $daynumofweek < $thisdaynumofweek) {
			# Tuesday (only if an earlier day in the week)
			return date("l", $time);
		} else if ($long) { # Get a longer version, in years/months/weeks/days
			if($months >= 11 && $months <= 13)
			{
				return "about a year ago";
			} else if($years > 0) {
				return $years > 1 ? "over $years years ago" : "over a year ago";
			} else if($days >= 25 && $days < 40) {
				return "about a month ago";
			} else if($months > 0) {
				return $months > 1 ? "$months months ago" : "over a month ago";
			} else if($days >= 6 && $days <= 9) {
				return "about a week ago";
			} else if($weeks > 0) {
				return $weeks > 1 ? "$weeks weeks ago" : "over a week ago";
			} else if($days > 0) {
				return $days > 1 ? "$days days ago" : "over a day ago";
			}
		} else if(date("Y", $time) != date("Y", time())) { # a totally different year.
			return date("F j, Y", $time);
		} else { # March 31
			return date("F j", $time);
		}
	}

	function since($date)
	{
		$time = strtotime($date);
		$secs = time() - $time;

		$days = $hours = $minutes = $weeks = $months = $years = 0;

		$minutes = intval($secs/60);
		$hours = intval($minutes/60);
		$days = intval($hours/24);
		$weeks = intval($days/7);
		$months = intval($days*12/365);
		$years = intval($days/365);

		if($years > 0) { return "$years year".($years>1?"s":""); }
		if($months > 0) { return "$months month".($months>1?"s":""); }
		if($weeks > 0) { return "$weeks week".($weeks>1?"s":""); }
		if($days > 0) { return "$days day".($days>1?"s":""); }

	}

	function caldate($date) # Make it look like a calendar, ie FEB (small), 23 (big)
	{
		ob_start();
		?>
		<div class='darkgreybg white margin5 paddingsides10' align='center'>
			<div class='small uppercase'><?= date("M", strtotime($date)); ?></div>
			<div class='larger'>
				<?= date("j", strtotime($date)); ?>
			</div>
		</div>
		<?
		return ob_get_clean();;
	}
}
