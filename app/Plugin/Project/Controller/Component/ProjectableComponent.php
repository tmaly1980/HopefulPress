<?
class ProjectableComponent extends Component
{
	var $uses = array('Projects.Project');

	function initialize(Controller $controller)
	{
		return parent::initialize($controller);
	}
	function startup(Controller $controller)
	{
		#if(!$this->enabled($controller)) { return; }

		# Grab project info if passed

		# In sub-records of project
		# XXX TODO need to load into form hidden field 
		#print_r($controller->request->pass);
		#print_r($controller->request->named);
		#print_r($controller->request->params);
		if(!empty($controller->request->params['project_id']))
		{
			Configure::write("project_id", $controller->request->params['project_id']);
		}
		if(!empty($controller->request->named['project_id']))
		{
			Configure::write("project_id", $controller->request->named['project_id']);
		}
		return parent::startup($controller);
	}

	function beforeRender(Controller $controller)
	{
		if($pid = Configure::read("project_id"))
		{
			$controller->set("project_id", $pid);
			$controller->set("project", $controller->Project->read(null, $pid));
			# Guessing whether we're in a project is based on $project var

			$controller->request->params['named']['project_id'] = $pid; # Hopefully this fires before AppCoreController's setNamedData()

		}
	}

}
