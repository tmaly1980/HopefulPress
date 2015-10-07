<?
class MailAliasesController extends AppController
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
			if($this->MailAlias->save($this->request->data))
			{
				return $this->setSuccess("Email alias saved",array('action'=>'index'));
			} else  {
				return $this->setError("Could not save alias");
			}
		}
		
		if(!empty($id))
		{
			$this->request->data = $this->MailAlias->read(null,$id);
		}
	}

	function admin_delete($id)
	{
		if($this->MailAlias->delete($id))
		{
			$this->setSuccess("Email alias deleted");
		} 
		$this->redirect(array('action'=>'index'));
	}

}
