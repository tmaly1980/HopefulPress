<?php

App::uses('JsonView', 'View');

class CoreJsonView extends JsonView {
	var $subDir = null; # Do not use json subfolder for content rendering.

	var $ajax = true; // set false for iframes...

	# Use the core Json view class, except embed the rendered 'action' and save to 'content'
	public function render($view = null, $layout = null) {

		$data = !empty($this->viewVars['json']) ? $this->viewVars['json'] : array();

		if (empty($this->_helpersLoaded)) { 
			$this->loadHelpers(); # Called too late otherwise, and defaults to core versions.
		}

		if(!empty($view)) # Render view file
		{
			# Do not use 'json' subfolder!
			$viewFileName = $this->_getViewFileName($view);
			$data['content'] = $this->_render($viewFileName);
			# *** If data is missing, we may have accidentally called $this->Json->set() instead of $this->set() !!!
		} # Otherwise, just render json data set via $this->Json->set()

		$this->set($data);
		$this->set("_serialize", array_keys($data));
		#error_log("RENDERING VIEW, DATA=".print_r(array_keys($data),true));

		# FORCE CONTENT TYPE!
		#$this->response->header(array("Content-Type"=>"application/json")); 
		# So get objects and not html/text

		#error_log("DATA=".print_r($this->request->data,true));

			#error_log("PRE_RENDER");

		# Detect if iframe vs XmlHttpRequest
		$content = parent::render($view, $layout);

		if($this->request->is('ajax'))
		{ # Normal ajax XMLHttpRequest
			header("Content-Type: application/json");
		} else { # Iframe.
			header("Content-Type: text/html");
			$content = "<textarea>$content</textarea>";
		}

		return $content;
	}

}
