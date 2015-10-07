<?
App::uses("NewsletterAppController", "Newsletter.Controller"); 
class MessagesController extends NewsletterAppController
{
	var $uses  = array('Newsletter.Message','Newsletter.Subscriber');

	function user_index()
	{
		$this->set("messages", $this->Message->find('all'));
	}

	function user_edit($id = null)
	{
		if(!empty($this->request->data))
		{
			if($this->Message->save($this->request->data))
			{
				$this->setSuccess("The message has been saved", array('action'=>'index'));
			} else {
				$this->setError("Could not save message. ".$this->Message->errorString());
			}
		} else  if (!empty($id)) { 
			$this->request->data = $this->Message->read(null, $id);
		}
	}

}
