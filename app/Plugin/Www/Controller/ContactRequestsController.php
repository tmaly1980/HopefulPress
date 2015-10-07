<?
App::uses("WwwAppController", "Www.Controller");

class ContactRequestsController extends WwwAppController
{
	var $uses = array('Www.ContactRequest');
	
	function manager_index()
	{
		$this->set("contactRequests", $this->model()->find('all'));
	}
	
	function manager_view($id)
	{
		$this->set("contactRequest", $this->model()->read(null,$id));
	}


	function add()
	{
		if(!empty($this->request->data))
		{
			if($this->model()->saveAll($this->request->data))
			{
				$this->sendManagerEmail("Website Contact Form", "Www.contact", array('contactRequest'=>$this->model()->read()));
				$this->setSuccess("Thanks for providing us with your question/comment. We will contact you shortly.","/");
			} else {
				$this->setError("Sorry, we were unable to process your information. ".$this->model()->errorString());
			}
		}

	}



}
