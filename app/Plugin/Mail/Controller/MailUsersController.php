<?
class MailUsersController extends AppController
{
	function admin_index()
	{
		return $this->redirect(array('controller'=>'mail','action'=>'index'));
	}

	# SOMEDAY worry about validation.... (unique usernames)
	function admin_edit($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->MailUser->save($this->request->data))
			{
				return $this->setSuccess("Email account saved",array('action'=>'index'));
			} else  {
				return $this->setError("Could not save account");
			}
		}
		
		if(!empty($id))
		{
			$this->request->data = $this->MailUser->read(null,$id);
		}
	}

	function admin_delete($id)
	{
		if($this->MailUser->delete($id))
		{
			$this->setSuccess("Email account deleted");
		} 
		$this->redirect(array('action'=>'index'));
	}

}
