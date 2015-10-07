<?
class StaticController extends WwwAppController
{
	public $uses = array();

	function render($view=null,$layout=null) { return $this->ab_render($view,$layout); } # AB testing...

	function message($page = 'index')
	{
		if(!empty($this->request->data['Message']))
		{
			$vars = array('data'=>$this->request->data);
			$this->managerEmail("Website inquiry", "manager/message", $vars);
			$this->setSuccess("Your message has been sent");
		}
		$this->redirect(array('action'=>'view',$page));
	}

	function index()
	{
		$this->setAction("view", "home");
	}

	function view()
	{
		$this->Tracker->track('Marketing');
		$url = join("/", func_get_args());
		if(empty($url)) { $url = 'home'; }

		if(method_exists($this, "view_$url"))
		{
			$viewMethod = "view_$url";
			return $this->$viewMethod();
		}

		$this->render($url);
	}

	function view_demo() # Here so we can track click-outs.
	{
		return $this->redirect("http://rescue.".Configure::read("default_domain"));
	}

	function faq_add()
	{
		if(!empty($this->request->data['Message']))
		{
			$vars = array('data'=>$this->request->data);
			$this->managerEmail("New website question", "manager/raw_message", $vars);
			$this->setFlashNotify('Your question has been sent');
		}
		$this->redirect(array('action'=>'view','faq'));
	}
}
