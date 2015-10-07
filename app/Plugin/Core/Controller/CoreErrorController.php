<?
App::uses("CakeErrorController", "Controller");

class CoreErrorController extends CakeErrorController
{
	var $helpers = array(
		'Html'=>array('className'=>'Core.CoreHtml')
	);

	function isBot()
	{
		return (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT']));
	}

	function bigproblem($exception)
	{
		error_log("PROCESSING ERROR:  ".get_class($this->viewVars['exception']));

		if(Configure::read("dev")) { return true;  } # Always on dev.

		if($this->isBot()) { return false; }
		$exceptionType = preg_replace("/Exception$/", "", get_class($exception));

		if($exceptionType == 'SiteNotFound') { return false; } # 404
		if($exceptionType == 'MissingController') { return false; } # 404
		if($exceptionType == 'MissingAction') { return false; } # 404
		#if($exceptionType == 'MissingView') { return false; } # 404
		
		return true; # Other things worth notifying about!
	}

	function beforeRender()
	{
		$exception = $this->viewVars['exception'];
		$exceptionType = preg_replace("/Exception$/", "", get_class($exception));
		if(in_array($exceptionType, array('SiteNotFound','FatalError','MissingMethod','MissingAction')))
		{
			$this->layout = 'plain';
		}
	}

	function afterFilter() # Notify admins via email.
	{ # After render
		if(!$this->bigproblem($this->viewVars['exception'])) { error_log("NO BIGGY, I DONT CARE"); return; } # Don't bother to notify.
		error_log("EMAILING SUPPORT..." . $this->viewVars['message']. "@ ".$this->viewVars['url']);

		#error_log(print_r($this->viewVars['error']->getTrace(),true));
		$vars = array(
			'message' => $this->viewVars['message'],
			'error' => $this->viewVars['error'],
			'url' => $this->viewVars['url'],
			'request' => $this->request,
			'session' => $this->Session->read()
		);

		$this->sendSupportEmail("Website Error - ".get_class($this->viewVars['exception']), "website_error", $vars);
	}
}
