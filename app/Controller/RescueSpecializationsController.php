<?
class RescueSpecializationsController extends AppController
{
	function rescuer_delete($id)
	{
		$rid = Configure::read("rescue_id");
		if($this->request->ext == 'json')
		{
			if(empty($rid)) { return $this->Json->error("Rescue not found"); }
			$this->RescueSpecialization->deleteAll(array('id'=>$id,'rescue_id'=>$rid));
			$this->Json->set("status","OK");
			$this->Json->render();
		} else { #FAILSAFE
			if(empty($rid)) { return $this->setError("Rescue not found","/rescues"); }
			$this->RescueSpecialization->deleteAll(array('id'=>$id,'rescue_id'=>$rid));
			$this->redirect(array('controller'=>'rescues','action'=>'edit','#'=>'specialization'));
		}

	}

}
