<?

class TrackerController extends AppController
{
	var $uses = array('BlogPageView','BlogVisit','MarketingPageView','MarketingVisit','MarketingVisitorBlacklist');
	var $helpers = array('Chart.Chart');


	function manager_ip($ip)
	{
		header("Content-Type: text/plain");
		echo "$ip => " . gethostbyaddr($ip)."\n\n";
		echo "GEOIP:";
		$geoip = HostInfo::geoip($ip);
		print_r($geoip);


		echo "WHOIS:";

		$whois = HostInfo::whois($ip); #  Hash format
		print_r($whois);
		exit(0);
	}

	function manager_delete_visit($type = 'Marketing', $id) # No need to ban, just remove.
	{
		if(!in_array($type, "Marketing","Blog","Site")) { return $this->setError("Invalid visit type",$this->request->referer()); }
		$visitModel = $type."Visit";
		$this->$visitModel->delete($id);
		$this->setSuccess("Deleted visit",$this->request->referer());
	}

	function manager_visits_graph($type = 'Marketing', $days = 30)
	{
		$this->set("type", $type);
		$this->set("days", $days);
	}

	function manager_top_visits($type = 'Marketing', $days = 30)
	{
		$this->set("type", $type);
		$this->set("days", $days);
		$model = "{$type}Visit";
		$this->loadModel($model);

		$this->set("visits", $visits = $this->{$model}->popular($days));
		$this->set("model", $model);

	}

	function manager_page_views($type = 'Marketing', $days = 30)
	{
		$this->set("type", $type);
		$this->set("days", $days);
		$model = "{$type}PageView";
		$this->loadModel($model);

		if(preg_match("/\d{4}-\d{2}-\d{2}/", $days))
		{
			$this->set("date", $days);
		}

		$this->set("pageViews", $pageViews = $this->{$model}->popular($days));
		$this->set("model", $model);
	}

	function manager_session($sid)
	{
		$this->set("marketingVisits", $this->MarketingVisit->find('all',array('conditions'=>array('MarketingVisit.session_id'=>$sid))));
		$this->set("blogVisits", $this->BlogVisit->find('all',array('conditions'=>array('BlogVisit.session_id'=>$sid))));
		$this->set("marketingPageViews", $mPageViews = $this->MarketingPageView->find('all',array('conditions'=>array('MarketingPageView.session_id'=>$sid))));
		$this->set("blogPageViews", $bPageViews = $this->BlogPageView->find('all',array('conditions'=>array('BlogPageView.session_id'=>$sid))));
		$this->set("session_id", $sid);
	}

	function manager_blacklist($ip)
	{
		$this->MarketingVisitorBlacklist->create();
		$this->MarketingVisitorBlacklist->saveField("ip", $ip);
		# Remove all records  of this IP!!!!
		$this->MarketingVisit->deleteAll(array('MarketingVisit.ip'=>$ip));
		$this->setSuccess("$ip has been blacklisted");
		$this->redirect($this->request->referer());
	}

	function manager_spam_referer()
	{
		if(!empty($this->request->query['refdomain']))
		{
			$refdomain = $this->request->query['refdomain'];

			# Remove all records  of this refdomain!!!!
			$this->MarketingVisit->deleteAll(array('MarketingVisit.refdomain'=>$refdomain));
			$this->setSuccess("All referrals from $refdomain have been removed");
		} else {
			$this->setError("No refdomain specified");
		}
		$this->redirect($this->request->referer());
	}

	function manager_page_view($type = 'Marketing', $date = null)
	{
		$href = $this->request->query['href'];
		$args = func_get_args();

		$this->set("type", $type);
		$model = "{$type}PageView";
		$this->loadModel($model);

		$this->set("model", $model);
		$cond = array("$model.url"=>$href,"MarketingVisitorBlacklist.id IS NULL");
		if(!empty($date)) { $cond[] = "DATE($model.created) = '$date'"; }
		$this->set("date", $date);

		$pageViews  = $this->paginate($model, $cond);
		if(empty($pageViews))
		{
			return $this->setError("Sorry, no page views for $href", "/manager");
		}
		foreach($pageViews as &$view)
		{
			$sid = $view[$model]['session_id'];
			$id = $view[$model]['id'];
			$referer = $view[$model]['refdomain'].$view[$model]['refpath'];
			if(!empty($view[$model]['refqs'])) { $referer .= "?".$view[$model]['refqs']; }

			$previous = $this->{$model}->find('first', array('conditions'=>array(
				"$model.session_id"=>$sid,"$model.id < $id"),"order"=>"$model.id DESC"));
			$next = $this->{$model}->find('first', array('conditions'=>array(
				"$model.session_id"=>$sid,"$model.id > $id"),"order"=>"$model.id ASC"));

			$view['Previous'] = !empty($previous[$model]) ? $previous[$model] : null;
			$previousurl = $view['Previous']['url'];
			$view['Next'] = !empty($next[$model]) ? $next[$model] : null;
			$nexturl = $view['Next']['url'];

			if(empty($topReferers[$referer])) { $topReferers[$referer] = 0; }
			$topReferers[$referer]++;

			if(empty($topNext[$nexturl])) { $topNext[$nexturl] = 0; }
			$topNext[$nexturl]++;

			if(empty($topPrevious[$previousurl])) { $topPrevious[$previousurl] = 0; }
			$topPrevious[$previousurl]++;

		}
		arsort($topPrevious);
		arsort($topNext);
		arsort($topReferers);

		$this->set("topPrevious", $topPrevious);
		$this->set("topNext", $topNext);
		$this->set("topReferers", $topReferers);

		$this->set("pageViews", $pageViews);
		#$this->set("count", $this->{$model}->field('COUNT(id)', $cond));
	}

	function chart($model  = 'MarketingPageView',$days = 7) # BlogPageView, MarketingVisit, BlogVisit
	{
		$url = !empty($this->request->query['href']) ? $this->request->query['href'] : null;
		$date = !empty($this->request->query['date']) ? $this->request->query['date'] : null;

		$this->{$model}->virtualFields['total'] = "COUNT($model.id)";
		$this->{$model}->virtualFields['created_ymd'] = "DATE($model.created)";
		$this->{$model}->virtualFields['created_hour'] = "HOUR($model.created)";

		$cond  = array("MarketingVisitorBlacklist.id IS NULL");
		if(!empty($url)) { $cond["$model.url"] = $url; }

		if(!empty($date))
		{
			$data = $this->{$model}->plotdates("$model.created","$model.total",array('start'=>"$date 00:00:00",'end'=>"$date 23:59:59",'datespan'=>'day','group'=>'created_hour','conditions'=>$cond,'recursive'=>0),true);

		} else {
			$start = date('Y-m-d',strtotime("$days days ago"));
			$data = $this->{$model}->plotdates("$model.created","$model.total",array('start'=>$start,'datespan'=>'month','group'=>'created_ymd','conditions'=>$cond,'recursive'=>0),true);
		}

		return $this->Json->render($data);
	}

}
