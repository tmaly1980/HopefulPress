<?
# Core stuff common to all apps
# AppController still needs to exclusively define components, helpers, etc.
#

App::uses("HttpSocket", "Network/Http");

class AppCoreController extends Controller
{
	var $other_components = array("Core.Json",'Paginator'); # Needs different name since not auto loaded.
	var $fake = 1024;
	var $who = 'us';

	# AB Testing
	# We can either have files named view.WHATEVER.ctp
	# We can also have login INSIDE actions, to mark a different variant (ie page redirect vs render)
	function ab_render($view) # Called from controller-level render()
	{
		if($variant = $this->ab_variant($view))
		{
			$view = $variant;
			error_log("VARIANT FOUND=$variant");
		}

		return parent::render($view);
	}

	function ab_action($variants = array()) # List method variants to randomly pick. Inside controller rather than view.
	{
		if(empty($variants)) { return; } # Don't know what to do, just render like normal.

		$action = $this->request->params['action']; # ie 'signup' (might render pricing choices, might just go to signup page - ie is pricing page a hurdle?)
		$sesskey = "ABView.$plugin.$controller.$action";

		$variant = $this->Session->read($sesskey);
		if(!empty($variant) && !method_exists($this, "{$action}_{$variant}")) # Invalid.
		{
			$variant = null;
		}

		if(empty($variant))
		{
			$ix = rand(0,count($variants)-1);
			$variant = $variants[$ix];
		}

		$method = "{$action}_{$variant}";

		if(method_exists($this, $method)) # Do it and record!
		{
			$this->ab_view = $action;
			$this->av_variant = $variant;
			return $this->setAction($method); # Will render THEIR view file.
		}
		#  Else, act as normal.
	}

	/*
	Determining which one is "more effective".... What is the end result we're testing against?
	Maybe we can define in config file? (equally as easy as modifying view files)
	CALL TO ACTION:
		{ model: Site } // record created with marketing_visit_id matching
		{ page: array("/pages/features","/pages/signup") } // one or more pages to check effective click thru (ie could have changed labels)
	*/

	# This can be  modified to check more than just a,b variants. Turning into single function.
	function ab_variant($view) # Check if variant file exists... Assumes no theme and file in standard location.
	{
		$plugin = $this->request->params['plugin'];
		$controller = $this->request->params['controller'];

		# To give test results a bit more meaning, we can name whatever we want.... ie home.screenshot.ctp , home.photo.ctp
		$variantPrefix = "View".DS.Inflector::camelize($this->request->params['controller']).DS.$view;

		if(!empty($this->request->params['plugin']))
		{
			$variantPath = APP."Plugin".DS.Inflector::camelize($this->request->params['plugin']).DS.$variantPrefix;
		} else {
			$variantPath = APP.$variantPrefix;
		}

		error_log("VARIANT_PATH=$variantPath");

		$variant = !empty($this->request->query['variant']) ? $this->request->query['variant'] : # Let them be explicit!
			$this->Session->read("ABView.$plugin.$controller.$view");

		error_log("SESSION $plugin.$controller.$view=$variant");

		# Only render variants when there is no view file without  a variant (ie home.ctp)
		if(file_exists("$variantPath.ctp")) { return false; }
		# Once we're done with a test, we can move the superior file over to the proper view file - stops testing.

		if(!empty($variant) && !file_exists("$variantPath.$variant.ctp")) # Invalid, removed, done testing.
		{
			$variant = null;
			$this->Session->delete("ABView.$plugin.$controller.$view");
		}

		if(!$variant && ($files = glob("$variantPath.*.ctp")) && !empty($files)) { # Variants exist but none chosen yet.
			error_log("VARIANT FILES FOUND=".print_r($files,true));

			$ix = rand(0,count($files)-1);
			error_log("IX=$ix");
			$file = $files[$ix];
			$file_parts = split("[.]", $file);
			error_log("FILE_PARTS=".print_r($file_parts,true));
			$ext = array_pop($file_parts);
			$variant = array_pop($file_parts);

			error_log("FILE=$file, VARIANT=$variant");


			$this->Session->write("ABView.$plugin.$controller.$view",$variant);

			#  Need to save BOTH since may differ from action name/method
			$this->ab_view  = $view;
			$this->ab_variant = $variant; # Tracker will know after it determines visit_id, to save...
			#$this->abview_save($variant); # Only need to save first time.
			# Called via Tracker now, after visit_id calculated.
		}

		if(!empty($variant))
		{
			error_log("VARIANT USING=$view.$variant");
			$view = "$view.$variant";
			# Record which one was chosen
			return $view;
		}

		return false;
	}

	function abview_save()
	{
		if(empty($this->ab_view)) { return;  }
		if(empty($this->ab_variant)) { return;  }

		$this->loadModel("Core.ABResult");
		error_log("SESSION (Tracker.visit_id ????)=".print_r($this->Session->read(),true));

		$data = array(
			'plugin'=>!empty($this->request->params['plugin']) ? $this->request->params['plugin'] : null,
			'controller'=>$this->request->params['controller'],
			'view'=>$this->ab_view,  # Might not be action name.
			'variant'=>$this->ab_variant,
			'session_id'=>session_id(),
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'marketing_visit_id'=>$this->Session->read("Marketing.visit_id"),
		);
		error_log("SAVING RESULT=".print_r($data,true));
		$this->ABResult->save($data);
	}

	#######################################
	# Convenience functions
	function beforeFilter()
	{
		App::uses("HostInfo", "Core.Lib");

		$this->redirectIpRequest();

		$this->setDebugLevel();

		# Components WE need... not called automatically
		foreach($this->other_components as $k=>$v)
		{
			$component = is_array($v) ? $k : $v;
			$params = is_array($v) ? $v : array();

			list($plugin,$class) = pluginSplit($component);

			$this->{$class} = $this->Components->load($component,$params);
			$this->{$class}->startup($this);
		}
		parent::beforeFilter();
	}

	function redirectIpRequest() # Redirect IP requests to hostname version.
	{
		if(!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['SERVER_ADDR']) && $_SERVER['HTTP_HOST'] == $_SERVER['SERVER_ADDR'])
		{
			$this->redirect("http://".HostInfo::default_domain());
		}
	}

	function is_god() # Implement at app level 
	{
		return false;
	}
	
	function setDebugLevel()
	{
		$domain = HostInfo::domain();
		if(
			!$this->RequestHandler->isMobile() &&
			(preg_match("/malysoft/", $domain) || !empty($_REQUEST['debug']) || !empty($_SESSION['debug']) || $this->is_god()) 
		)
		{
			Configure::write("debug", 2);
		} else {
			Configure::write("debug", 0);
		}
	}

	function viewByName($value) # Go to view page of item, based on displayField value
	{
		$model = $this->{$this->modelClass};
		$primaryKey = $model->primaryKey;
		$displayField = $model->displayField;

		$entry = $this->{$this->modelClass}->find('first', array('field'=>array($primaryKey), 'conditions'=>array($displayField=>$value)));
		$id = !empty($entry[$this->modelClass][$primaryKey]) ? $entry[$this->modelClass][$primaryKey] : null;
		$params = array('action'=>'view',$id);
		foreach($this->request->params['named'] as $k=>$v) { $params[$k] = $v; } // Keep named parameters!
		$this->redirect($params);
	}

	function invalid()
	{
		throw new NotFoundException('Page Not Found');
	}

	function notFound($model = null, $redirect = null)
	{
		if(empty($model)) { $model = $this->modelClass; }
		# Go to index and notify of page not found...
		$thing = $this->$model->thing();
		#throw new NotFoundException(__("Invalid $thing"));

		if(empty($model))
		{
			$model = $this->modelClass; # Default.
		}
		$url = Router::url();

		if(empty($redirect))
		{
			$redirect = array('action'=>'index');
		}

		$this->setFlash("Sorry, the page could not be found:<br/><br/> <b>$url</b>", $redirect);
		# Controller can modify if needs to be something else. can call parent::notFound with custom redirect, etc.
	}

	function underConstruction()
	{
		$this->render("/construction");
	}

	# Easier handler for record existing.
	function check($id, $model = null) # USED IN SOME HP CODE
	{
		if(empty($model))
		{
			$model = $this->modelClass; # Default.
		}
		$this->$model->id = $id;
		if (!$this->$model->exists()) {
			# XXX we need to someday find a better way to deal with this, ie redirect to a USEFUL place.
			$this->notFound($model);
			return false;
		}
		return true;
	}


	#######################################
	# Flash messages
	function setError($msg, $redirect = null)
	{
		return $this->setFlash($msg, $redirect, 'danger');
	}

	function setInfo($msg, $redirect = null)
	{
		return $this->setFlash($msg, $redirect, 'info');
	}

	function setSuccess($msg, $redirect = null)
	{
		return $this->setFlash($msg, $redirect, 'success');
	}

	function setWarn($msg, $redirect = null)
	{
		return $this->setFlash($msg, $redirect, 'warning');
	}

	function setFlash($msg, $redirect = null, $level = 'warning') { # FORCES internal xml/json rendering.... if we have a CTP template, we need to set serialize(false) in the end....
		# Will process as XML/JSON if .xml/.json
		$icons = array(
			'warning'=>'warning-sign',
			'success'=>'ok-circle',
			'danger'=>'exclamation-sign',
			'info'=>'info-sign',
		);

		if($this->request->is('ajax') || $this->request->is('xml') || $this->RequestHandler->ext == 'json' || $this->RequestHandler->ext == 'xml')
		{
			$this->serialize(array("message"=>$msg,"status"=>$level));
		} else {
			$this->Session->setFlash($msg, 'alert', array('plugin'=>'Core','class'=>"alert-$level",'icon'=>!empty($icons[$level]) ? $icons[$level] : null));
			# If JSON/XML, ignore any redirect
			if(!empty($redirect)) { $this->redirect($redirect); }
		}

	}

	function serialize($add = array()) # Can set values as well, or just list variable names.
	{
		$serialize = !empty($this->viewVars['_serialize']) ? $this->viewVars['_serialize'] : array();
		if($add === false)
		{
			unset($this->viewVars['_serialize']); # Reset
			unset($serialize);
		} else {

			$add = !is_array($add) ? array($add) : $add;
			foreach($add as $k=>$s)
			{
				if(!is_numeric($k)) # SET as well
				{
					$this->set($k, $s);
					$s = $k;
				}

				$serialize[] = $s;
			}
			$this->set("_serialize", $serialize);
		}

		return $serialize; # Full list.
	}

	function json() { return $this->request->is("json") || $this->RequestHandler->ext == 'json'; }
	function xml() { return $this->request->is("xml") || $this->RequestHandler->ext == 'xml'; }

	#######################################
	# Form helpers (call in beforeRender)
	function beforeRender()
	{
		$this->setNamedData();
		$this->setVars();

		if(!empty($this->request->query['sql']))
		{
			header("Content: text/plain");
			$this->{$this->modelClass}->sqlDump();
			exit(0);
		}
		return parent::beforeRender();
	}

	function setVars()
	{
		# Go thru route prefixes and mark variables
		$prefixes = Configure::read("Routing.prefixes");
		foreach($prefixes as $prefix)
		{
			$this->set("is_$prefix", !empty($this->request->params[$prefix]));
		}

	}

	function me() {
		return $this->Auth->me(); # in UserCore Auth
	}

	function user($key,$val=null) # Retrieve OR UPDATE user session info
	{
		if(!isset($val))
		{
			return $this->Auth->user($key);
		} else if(!empty($key) && isset($val) && $this->Auth->me()) { # Update account
			$this->{$this->Auth->userModel}->id = $this->Auth->me();
			$this->{$this->Auth->userModel}->saveField($key,$val);
			$user = $this->{$this->Auth->userModel}->read();
			return $this->Auth->login($user); 
			# issues with keeping keys??? but we want to access Rescue for User...
		}
	}

	function validateField($model,$field = null)
	{
		if(empty($field)) { $field = $model; $model = null; }
		if(empty($model)) { $model = $this->modelClass; }

		if(!empty($this->request->data))
		{
			# Some fields may depend on each other, so we need to set everything (ie password/password2), but only validate a certain field.
			$this->model()->set($this->request->data);
			$errors = null;
			if(($errors = $this->model()->validates(array('fieldList'=>array($field)))) === true)
			{
				$response = array('valid'=>true);
			} else {
				$errors = $this->model()->validationErrors;
				# XXX RETRIEVE MESSAGE $errors...
				$message = join(". ", $errors[$field]);
				$response = array('valid'=>false,'message'=>$message);
			}
		} else {
			$response = array('status'=>'ok');
		}
		$this->json_render($response);
	}

	function json_render($data)
	{
		return $this->Json->render($data);
	}
	
	function setNamedData()
	{
		# Automatically set named parameters into form if we're add/edit, otherwise they have to manually retrieve.
		$action = $this->request->params['action'];
		$prefix = !empty($this->request->params['prefix']) ? 
			$this->request->params['prefix'] : null;

		$action = preg_replace("/^{$prefix}_/", "", $action);

		
		if(in_array($action, array('add','edit')))
		{
			#print_r($this->request->params['named']);
			if(!empty($this->request->params['named']))
			{
				foreach($this->request->params['named'] as $k=>$v)
				{
					$this->request->data[$this->modelClass][$k] = $v;
				}
			}
		}
	}

	########################################
	# Model related.
	function thingVars() { return $this->{$this->modelClass}->thingVars(); }
	function thingVar() { return $this->{$this->modelClass}->thingVar(); }
	function things() { return $this->{$this->modelClass}->things(); }
	function thing() { return $this->{$this->modelClass}->thing(); }
	function ucThing() { return ucwords($this->{$this->modelClass}->thing()); }
	function model($model = null) { return !empty($model) ? $this->{$model} : $this->{$this->modelClass}; }
	function read($id) { return $this->{$this->modelClass}->read(null, $id); } # Overwritten by publishable

	function json_exists()
	{
		header("Content-Type: application/json");
		$cond = $this->request->query;
		$entry = $this->first($cond);
		echo json_encode($entry);
		exit(0);
	}


	#######################################
	# Email related
	function emailer()
	{
		if(empty($this->CoreEmail))
		{
			throw new Exception("CoreEmail was not loaded in AppController - required for sending emails");
		}
		return $this->CoreEmail;
	}

	function subscriberEmail($user, $subject, $template, $vars) { return $this->sendSubscriberEmail($user, $subject, $template, $vars); }
	function sendSubscriberEmail($user, $subject, $template, $vars = array())
	{
		return $this->emailer()->subscriberEmail($user, $subject, $template, $vars);
	}

	function userEmail($user, $subject, $template, $vars) { return $this->sendUserEmail($user, $subject, $template, $vars); }
	function sendUserEmail($user, $subject, $template, $vars = array())
	{
		# $vars['site'] = ...
		return $this->emailer()->userEmail($user, $subject, $template, $vars);
	}

	function supportEmail($user, $subject, $template, $vars) { return $this->sendSupportEmail($user, $subject, $template, $vars); }
	function sendSupportEmail($subject, $template, $vars = array())
	{
		# $vars['site'] = ...
		return $this->emailer()->supportEmail($subject, $template, $vars);
	}

	function managerEmail($subject, $template, $vars = array()) { return $this->sendManagerEmail($subject, $template, $vars); }
	function sendManagerEmail($subject, $template, $vars = array())
	{
		# $vars['site'] = ...
		return $this->emailer()->managerEmail($subject, $template, $vars);
	}


	function sendEmail($to, $subject, $template, $vars = array())
	{
		# $vars['site'] = ...
		return $this->emailer()->email($to, $subject, $template, $vars);
	}

	function email($to, $subject, $template, $vars = array()) { return $this->sendEmail($to, $subject, $template, $vars); }

	######################
	#

	function noContent($title = null)
	{
		if(empty($title) && $title !== false)
		{
			$title = $this->{$this->modelClass}->page_title();
			if(in_array($this->action, $this->prefixlist('index')))
			{
				$title = Inflector::pluralize($title);
			}
		}
		$this->set("title", $title);
		$this->render("/empty_page");
	}

	function prefixlist($actions) # list of actions with all prefixes
	{
		$outlist = array();
		if(!is_array($actions)) { $actions = array($actions); }
		$prefixes = Configure::read("Routing.prefixes");
		$outlist = array();

		foreach($actions as $action)
		{
			$outlist[] = $action;

			foreach ($prefixes as $prefix)
			{
				$outlist[] = "{$prefix}_$action";
			}
		}

		return $outlist;
	}

	################### 
	# New stuff
	function slugify($field = 'url') # Generate url from title
	{
		$slug = $this->model()->slugify($this->request->data);
		$this->Json->set($field, $slug);
		return $this->Json->render();
	}

	######################
	# Editable moved to app_controller!

	function http_post($url, $params=array(),$headers=array(),$json=false)
	{
		$socket = new HttpSocket();
		$response = $socket->post($url,$params,$headers);
		if($response->code != 200) { error_log("GETTING $url,FAILED=".$response->reasonPhrase); }
		$body = $response->body();
		return !empty($json) ? json_decode($body,true) :  $body;
	}

	function http_get($url, $params=array(),$headers=array(),$json=false)
	{
		$socket = new HttpSocket();
		$response = $socket->get($url,$params,$headers);
		if($response->code != 200) { error_log("GETTING $url,FAILED=".$response->reasonPhrase); }
		$body = $response->body();
		return !empty($json) ? json_decode($body,true) :  $body;
	}

	######################
	# used by scraper, etc.
	#
	function get_webpage_content($url, $return_status = false)
	{
		$socket = new HttpSocket();
		$content = $socket->get($url);
		if(empty($content))
		{
			error_log("SOCK=".print_r($socket,true));
			error_log("get_webpage_content($url) failed: ".print_r($socket->response,true));
		}
		#error_log("RES=".print_r($socket->response,true));

		$status = $socket->response['status']['code'];
		#error_log("FOR $url, got $status STATUS, CONTEN=$content");

		if($status >= 300 && $status < 400) # redirect.
		{
			$location = $socket->response['header']['Location'];
			return $this->get_webpage_content($location, $return_status);
			
		}

		if($return_status)
		{
			return array($content, $status);
		}
		return $content;
	}
	
	function view_redirect($id)
	{
		return $this->redirect(array('action'=>'view',$id));
	}

	# Since mostly the same, put here to share.
	public function _delete($id) {
		$this->{$this->modelClass}->id = $id;
		if (!$this->{$this->modelClass}->exists()) {
			return $this->notFound();
		}
		if ($this->{$this->modelClass}->delete()) {
			return $this->setSuccess('The '.$this->{$this->modelClass}->thing(). ' has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The '.$this->{$this->modelClass}->thing(). ' could not be deleted: '.$this->{$this->modelClass}->errorString());
		}
	}

	# Default edit
	function _edit($id = null)
	{
		$thing = $this->thing();
		if (!empty($this->request->data)) { 
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setSuccess("The $thing has been saved", array('action'=>'view',$this->{$this->modelClass}->id));
			} else {
				$this->setError("The $thing could not be saved: ". $this->{$this->modelClass}->errorString());
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
	}

	function ajax_updating($target = null) # Specifies target container
	{
		$updating = !empty($_SERVER['HTTP_X_UPDATE']) ? $_SERVER['HTTP_X_UPDATE'] : null;
		return empty($target) ? $updating : $target == $updating;
	}

	function isAuthorized($user=null) # $this->Auth->prefixes is likely good enough.
	{
		return true;
	}

	function geoip()  # Location of user, kinda.
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		# Functions.
		$rc = App::import("Vendor", "Tracker.geoip");
		$rc = App::import("Vendor", "Tracker.geoipcity");
                $gi = geoip_open(APP."/Plugin/Tracker/Vendor/GeoLiteCity.dat", GEOIP_STANDARD);
                $result = geoip_record_by_addr($gi, $ip);
                geoip_close($gi);
		if(empty($result)) { # FAKE
			$result = new Object();
			$result->ip = $ip;
			$result->country_code = 'US';
			$result->city = 'Cherry Hill';
			$result->region_code = 'NJ';
		}
                $geoip = get_object_vars($result);
		return $geoip;
	}

	# BULK RECORD IMPORTING
	function _import_template()
	{
		header("Content-Type: application/octet-stream");
		$things = strtolower(Inflector::pluralize(Inflector::underscore($this->modelClass)));
		header("Content-Disposition: inline; filename='$things.csv'");
		if(empty($this->model()->import_fields))
		{
			return $this->setError("Import fields not configured.", array('action'=>'add'));
		} else {
			echo join($this->delim, $this->model()->import_fields)."\n";
			exit(0);
		}
	}

	function _import() 
	{
		if(!empty($this->request->data[$this->modelClass]['file']))
		{
			$this->Upload->content_only(true);
			$content = $this->Upload->upload($this->request->data[$this->modelClass]['file']); 
			$rows = $this->Csv->import($this->request->data[$this->modelClass]['file']['tmp_name'],null,array('delimiter'=>$this->delim));

			$ids = array();

			if(empty($rows))
			{
				return $this->setError("No data found in file provided.");
			}
			$keys = array_keys($rows[0][$this->modelClass]);
			# If any keys don't match field, abort...
			foreach($keys as $key)
			{
				if(!$this->model()->hasField($key))
				{
					return $this->setError("Invalid field '$key'. Improper file format. Use the template link below.");
				}
			}

			# IMPLEMENT ATOMIC IMPORT, so lines before failures ARENT added multiple times!!!!!
			$this->model()->getDataSource()->begin();

			$i = 1;
			foreach($rows as $row)
			{
				$i++;
				$this->model()->create(); # Clear id, so save into new records.
				if(!$this->model()->saveAll($row))
				{
					$this->model()->getDataSource()->rollback();
					return $this->setError("Error Line $i: ".$this->model()->errorString());
				}
				$ids[] = $this->model()->id;
			}

			$this->model()->getDataSource()->commit();
			$this->setSuccess(count($ids)." records successfully imported",array('action'=>'index','added'=>$ids));
		}
	}

	function loadExplicitSession() #From cross-domain use.
	{
		$cookie = Configure::read("Session.cookie");
		if(!empty($this->request->query[$cookie]))
		{
			session_id($this->request->query[$cookie]);
		}
	}


}
