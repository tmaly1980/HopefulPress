<?
class JsonComponent extends Component
{
	var $data = array();
	var $vars = array();
	var $debug = false;

	function startup(Controller $controller) { $this->controller = $controller; }

	function notify($notify, $title = null)
	{
		$this->set("notify", $notify);
		$this->set("notify_title", $title);
	}

	function script($script) # Can only be used once.
	{
		$this->set("script", $script);
	}

	function replace($target)
	{
		$this->update($target, 'replace');
	}

	function append($target)
	{
		$this->update($target, 'append');
	}

	function dialogclose($callback =true)
	{
		$this->modalclose($callback);
	}

	function modalclose($callback = true)
	{
		$this->set("modalclose", $callback);
	}

	function update($target, $type = null) # replace (outer), append, insert, etc.
	{
		$this->set("target", $target); # Container to update, with 'content', if any.
		if(!empty($type))
		{
			$this->set($type, true); 
		}
	}

	function set($k,$v=null)
	{
		if(empty($this->controller->viewVars['json'])) { $this->controller->viewVars['json'] = array(); }
		if(empty($v) && is_array($k))
		{
			foreach($k as $ik=>$iv)
			{
				$this->vars[$ik] = $iv;
			}
		} else {
			$this->vars[$k] = $v;
		}

	}

	function setFlash($msg)
	{
		$this->error($msg);
	}

	function error($msg)
	{
		# We should get validation errors from the current model....
		$validErrors = $this->controller->{$this->controller->modelClass}->validationErrors;

		error_log("ERRROR, SETTING VALIDATION ERROR TO=".print_r($validErrors,true));

		if(!empty($validErrors))
		{
			$this->set("validationErrors", array($this->controller->modelClass=>$validErrors));
		}

		$this->set("error",$msg);
		return $this->render(); # Render immediately.
	}

	function redirect($url, $target = null) # Will do exit() at the same time, so call this LAST
	{
		# We need to use Json->redirect() instead of normal redirect, so client's content handler knows to replace location.href for whole window, not just modal.
		$this->set("redirect", Router::url($url));
		return $this->render(null);
	}

	function render($action = null, $target = null, $type = 'replace')
	{
		if(is_array($action))
		{
			$this->set($action);
			$action = null;
		}

		if(empty($this->vars['redirect']))
		{
			# Catch existing flash messages and interject. ONLY if not redirecting (redirecting would normally show success)
			$messages = Set::extract("/message", $this->controller->Session->read("messages"));
			$this->controller->Session->delete("messages");
			if(!empty($messages))
			{
				$this->error(join(". ", $messages));
			}
		}

		#error_log("RENDERING JSON $action, $target, $type");
		if(!empty($target))  # If controller doesn't set, then we base off form submission or link parameter (target)
		{
			$this->update($target, $type);
		}
		App::uses('CoreJsonView', 'Core.View');

		$this->controller->viewClass = 'Core.CoreJson'; # No conflict with existing.

		$this->controller->viewVars['json'] = $this->vars;
		#error_log("JSON RENDER, VARS=".print_r($this->vars,true));
		$this->vars = array(); # Clear.

		echo $this->controller->render($action);
		exit(0);
	}

}
