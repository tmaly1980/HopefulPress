<?
App::import("Controller", "Controller");
App::import("Core", "Router");
App::import("Network", "CakeRequest");

class HpShell extends Shell
{
	var $controller = null;
	var $uses = array();
	var $components = array();
	var $disabled_components = array('Auth');#,'Multisite.Multisite'); # Never load.

	function startup()
	{
		// Suppress conventional 'welcome' screen.
	}

	function initialize()
	{
		#error_log( "INIT PAERNT");
		$this->controller = new Controller(new CakeRequest());

		foreach($this->uses as $model)
		{
			$this->loadModel($model);
		}

		foreach($this->components as $component)
		{
			list($plugin,$cmp) = pluginSplit($component);
			#$this->$cmp = $this->controller->Components->load($component);
			$this->$cmp = $this->componentLoader($component); # We must call this so they properly call initialize(), so $uses gets loaded
		}
	}

	function loadModel($model = null, $id = null)
	{
		list($plugin, $modelClass) = pluginSplit($model);
		$this->{$modelClass} = $this->controller->{$modelClass} = ClassRegistry::init($model);
	}

	function componentLoader($component) # Recursive...
	{
		#error_log("LOADING: $component");
		if(in_array($component, $this->disabled_components)) { return; } # Skip.

		list($plugin,$cmp) = pluginSplit($component);

		$cmp = $this->controller->Components->load($component);

		if(!empty($cmp->components)) {
			foreach($cmp->components as $subcomponent)
			{
				list($subplugin, $subcomp) = pluginSplit($subcomponent);
				$sub = $this->componentLoader($subcomponent);
				$cmp->$subcomp = $sub;
			}
		}

		if(method_exists($cmp, "initialize"))
		{
			$cmp->initialize($this->controller); # So site component gets direct access to models it wants.
		}
		if(method_exists($cmp, "startup"))
		{
			$cmp->startup($this->controller);
		}

		return $cmp;
	}

	# We name all functions with underscores, so we can pass through main() and use same functions directly and via command line
	# Otherwise, if called from the command line, the args will be in $args instead of passed as params. so not very modular...
	function main()
	{ # If a function doesnt exist named after the arg, then we end up here. So we can route funcs w/underscore prefix and pass args.
		if(empty($this->args))
		{
			return $this->help();
		}
		$cmd = array_shift($this->args);

		if(method_exists($this, "_$cmd"))
		{
			call_user_func_array(array($this, "_$cmd"), $this->args); # Pass cmd line args as params, so can use directly or scripted.
		} else {
			$this->hr();
			$this->out("Invalid command '$cmd'");
			return $this->help();
		}
	}


}
