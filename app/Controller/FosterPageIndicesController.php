<?
App::uses("SingletonController", "Singleton.Controller");
class FosterPageIndicesController extends SingletonController
{
	var $uses = array('FosterPageIndex','Foster','FosterFaq','FosterDownload','FosterPage','FosterForm','RescueFoster');

	function view($id=null)
	{
		$this->set("statuses", $this->RescueFoster->statuses);
		$this->set("downloads", $this->FosterDownload->find('all'));
		$this->set("pages", $this->FosterPage->find('all'));
		$this->set("faqs", $this->FosterFaq->find('all'));
		$this->set("fosterForm", $this->FosterForm->singleton()); # Create if needed. Unless disabled.

		# Try to pre-load form if can.
		if($user_id = $this->me())
		{
			$this->request->data = $this->Foster->findByUserId($user_id);
		}
		return parent::view($id);
	}

}
