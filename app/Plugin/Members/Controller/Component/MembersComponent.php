<?
class MembersComponent extends Component
{
	function startup(Controller $controller) # XXX set up ACL to protect...
	{
		if(!empty($controller->request->params['members']))
		{
			Configure::write("members_only", true);
			$controller->Auth->deny('*');
			# Members area by definition is available to ALL users.... and everyone has equal access to create, UNLESS an admin
		}
	}

	function beforeRender(Controller $controller)
	{
		if(!empty($controller->request->params['members']))
		{
			$controller->set("memberPage", $controller->MemberPage->first(array('enabled'=>1)));
		}
	}

}
