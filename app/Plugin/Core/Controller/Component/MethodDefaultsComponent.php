<?
# Maps add to edit methods, and any admin prefixes to non-admin defaults, etc.
class MethodDefaultsComponent extends Component
{
	var $prefixMaps = array(
		'admin'=>'', # admin_ methods default to public versions.
		# Can add other prefix mappings here, ie 'admin'=>'processor'
	);

	function initialize(Controller $controller)
	{
		$this->controller = $controller;
		$this->catchMethodDefaults();
	}

	# add=>edit method
	# admin_method => method, etc.
	# Will use same controller logic AND view file
	# (be explicit about method if want CUSTOM/OWN view file)
	function catchMethodDefaults()
	{
		$controller = $this->controller;

		$prefix = !empty($controller->request->params['prefix']) ? $controller->request->params['prefix'] : null;
		$prefix_ = !empty($prefix) ? $prefix.'_' : null;
		$method = $controller->action;
		$action = preg_replace("/^{$prefix}_/", "", $method);

		# Try edit if add doesnt exist.
		# but If admin_add,  try  admin_edit
		if ($action == 'add' && !method_exists($controller, $method) && method_exists($controller, "{$prefix_}edit"))
		{
			$action = "{$prefix_}edit";
		}


		# Set original action so form can parse url properly....
		$controller->request->params['orig_action'] = $controller->action;

		# Let us use a lower level access method if our levels doesn't exist.
		# Will use the same view file.
		# To use a different/modified view file, simply explicitly call the other method from within the higher level method.

		foreach($this->prefixMaps as $srcp=>$destps)
		{
			$destps = !is_array($destps) ? array($destps) : $destps; # Allow multiple fallbacks (array syntax). admin=>array('user','')

			foreach($destps as $destp)
			{
				$destaction = !empty($destp) ? "{$destp}_$action" : $action;
				$destaction_edit = ($action == 'add' && !empty($destp)) ? "{$destp}_edit" : "edit";
				if(!method_exists($controller, $method) && $prefix == $srcp)
				{
					#echo "NO $method / $action ($destaction vs $destaction_edit), ";
					if(method_exists($controller, $destaction)) # Fall back on next lower level of access.
					{
						#echo "YES $destaction, ";
						$controller->request->params['fake_prefix'] = $destp;
						$controller->request->params['action'] = $destaction;
						$controller->view = $destaction;
						return; # Processed!
					# Try dealing with add as edit
					} else if ($action == 'add' && method_exists($controller, $destaction_edit)) { # add => user_edit
						#echo "YES2 $destaction, ";
						$controller->request->params['fake_prefix'] = $destp;
						$controller->request->params['action'] = $destaction_edit;
						$controller->view = $destaction_edit;
						return; # Processed!
					}
				}
			}
		}

		# catch add to edit
		if(!method_exists($controller, $method) && method_exists($controller, "$prefix_$action")) { # Catch add to edit
			$controller->request->params['fake_prefix'] = "$prefix";
			$controller->request->params['action'] = "$prefix_$action";
			$controller->view = "$prefix_$action";
		}
	}
}
