<?php
App::uses('AppController', 'Controller');
class EventsController extends AppController {

	var $paginate = array(
		'Event'=>array(
			'order' => 'Event.start_date DESC' # Default when past.
		)
	);

/* Actions */
	function leftnav() { return $this->leftnav_media(); }

	public function index() {
		$this->track();
		#$this->track();
	 	#if (!$this->Event->count()) { return $this->noContent(); }

		$this->Event->recursive = 1;
		if(empty($this->request->params['named']['page']))
		{
			$this->set('upcoming_events', $this->Event->findAll(array('future'=>1),null,'Event.start_date ASC'));
		}
		$this->set('previous_events', $this->paginate('Event', array('OR'=>array('future'=>0,'future'=>null))));
	}

	public function view($id = null) {
		$this->track();
	 	if (!$this->Event->count($id)) { return $this->invalid(); }
		$this->set('event', $this->Event->read(null, $id));
	}

/* Actions */

/*
	public function admin_index() {
	 	if (!$this->Event->count()) { return $this->noContent(); }

		$this->Event->recursive = 1;
		if(empty($this->request->params['named']['page']))
		{
			$this->set('upcoming_events', $this->Event->findAll(array('future'=>1),null,'Event.start_date ASC'));
		}
		$this->set('previous_events', $this->paginate('Event', array('future'=>0)));
	}

	public function admin_view($id = null) {
	 	if (!$this->Event->count($id)) { return $this->invalid(); }
		$this->set('event', $this->Event->read(null, $id));
	}
*/

	public function edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			error_log("SAVE_ALL=".print_r($this->request->data,true));
			if ($this->Event->saveAll($this->request->data)) {
				$created = $id ? "updated" : "created";
				$pid = !empty($this->request->data['Event']['project_id']) ? $this->request->data['Event']['project_id'] : array();
				$this->setSuccess("The event has been $created. ");#<a href='/admin/events/add' class='color green'>Add another event</a> or <a href='/admin/events' class='color'>view all events</a>".(!empty($pid)?" or <a href='/admin/projects/view/$pid'>go back to your project</a>":""));
				$this->view_redirect($this->Event->id);
			} else {
				$this->setError('The event could not be saved: '.$this->Event->errorString());
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->Event->read(null, $id);
		}
		$this->set("users", $this->User->find('list'));
	}

	function admin_bydate($year=null,$month=null,$day=null) { return $this->setAction('bydate',$year,$month,$day); }

	function bydate($year=null,$month = null, $day = null)
	{
		if(empty($year)) { list($year,$month,$day) = split("-", date("Y-m-d")); }
		else if(empty($month)) { $month = 1; $day = 1; }
		else if(empty($day)) { $day = 1; }
		$this->set("year", $year);
		$this->set("month", $month);
		$this->set("day", $day);

		$date = sprintf("%04u-%02u-%02u", $year,$month,$day);
		$events = $this->Event->find('all', array('conditions'=>array(" ( (YEAR(start_date) = '$year' AND MONTH(start_date) = '$month') OR (YEAR(end_date) = '$year' AND MONTH(end_date) = '$month')) ")));
		$this->set("events", $events);
	}

	function admin_calendar($year=null,$month=null)
	{
		return $this->setAction("calendar", $year,$month);
	}

	function calendar($year = null, $month = null)
	{
		if(empty($year)) { $year = date("Y"); $month = date("m"); }
		else if(empty($month)) { $month = "01"; }

		$this->Event->recursive = -1;
		$events = $this->Event->find('all', array('conditions'=>array(" ( (YEAR(start_date) = '$year' AND MONTH(start_date) = '$month') OR (YEAR(end_date) = '$year' AND MONTH(end_date) = '$month')) ")));
		$this->set("events", $events);

		$days = array();
		$daysofmonth = date("t", strtotime("$year-$month-01"));

		for($day = 1; $day <= $daysofmonth; $day++)
		{
			$days[$day] = array();

			$thisdate = sprintf("$year-$month-%02u", $day);
			foreach($events as $event)
			{
				$start_date = date("Y-m-d", strtotime($event["Event"]['start_date']));
				$end_date = !empty($event['Event']['end_date']) ? date("Y-m-d", strtotime($event["Event"]['end_date'])) : null;

				if(!empty($end_date))
				{
					if(strtotime($thisdate) >= strtotime($start_date) && strtotime($thisdate) <= strtotime($end_date))
					{
						$days[$day][] = $event;
					}
				} else {
					if($thisdate == $start_date)
					{
						$days[$day][] = $event;
					}
				}
			}
		}

		$this->set("days", $days);

		$this->set("year", $year);
		$this->set("month", $month);
		return $events;
	}

	function yearmonth($yearmonth)
	{
		if(empty($yearmonth) || !preg_match("/^(\d{4})(\d{2})$/", $yearmonth))
		{
			$yearmonth = date("Ym");
		}
		preg_match("/^(\d{4})(\d{2})$/", $yearmonth, $matches);
		list($regex, $year, $month) = $matches;
		$this->set("year", $year);
		$this->set("month", $month);
		return array($year, $month);
	}

	function admin_calendar_widget($year = null, $month = null)
	{
		$this->setAction("calendar_widget", $year, $month);
	}

	function calendar_widget($year = null, $month = null)
	{
		$this->calendar($year, $month);
	}

	function raw()
	{
		$events = $this->Event->findAll(array('future'=>1),null,'Event.start_date ASC');
		header("Content-Type: text/plain");
		print_r($events);
		exit(0);
	}

	function calendar_raw($year = null, $month = null)
	{
		header("Content-Type: text/plain");
		$events = $this->calendar($year, $month);
		$days = $this->viewVars['days'];
		print_r($days);
		print_r($events);
		exit(0);
	}

}
