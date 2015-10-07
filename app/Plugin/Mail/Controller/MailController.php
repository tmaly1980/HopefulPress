<?
class MailController extends AppController
{
	var $uses = array('MailUser','MailAlias');

	function admin_enable()
	{
		$this->Site->id = $this->get_site_id();
		$this->Site->saveField("email_enabled",1);
		$this->setSuccess("Email services enabled","/admin/mail");
	}

	function admin_disable()
	{
		$this->Site->id = $this->get_site_id();
		$this->Site->saveField("email_enabled",0);
		$this->setSuccess("Email services disabled","/admin/mail");
	}

	function admin_index()
	{
		$this->set("users", $this->MailUser->find('all'));
		$this->set("aliases", $this->MailAlias->find('all'));

	}

}
