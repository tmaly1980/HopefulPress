<?
App::uses("RescueAppController", "Rescue.Controller");
class AdoptionStoriesController extends RescueAppController
{
	var $uses  = array('Rescue.AdoptionStory','Rescue.Adoptable');

	# Do we allow them to insert adoption stories of animals NOT in db? 
	# ie good for credibility!

	function index()
	{
		$this->set("adoptionStories", $this->paginate('AdoptionStory'));
		$this->set("adoptables", $adoptables = $this->Adoptable->adopted_list());
	}

	function widget()
	{
		$this->set("adoptionStories", $this->paginate('AdoptionStory'));
		$this->set("adoptables", $adoptables = $this->Adoptable->adopted_list());
	}

	function user_edit($id=null) { return $this->setAction("user_edit_story",$id); }

	# Not sure why named this... but dont want to break.
	function user_edit_story($id=null)
	{
		if(!empty($this->request->data))
		{
			if($this->AdoptionStory->saveAll($this->request->data))
			{
				return $this->setSuccess("Adoption story saved",array('action'=>'view',$this->SucessStory->getInsertID())); # Needs to be mysql call? ->id fails.
			} else  {
				$this->setError("Could not save adoption story.  ".$this->AdoptionStory->errorString());
			}
		} else if (!empty($id)) {
			$this->request->data = $this->AdoptionStory->read(null,$id);
		} 
		$this->set("adoptables", $adoptables = $this->Adoptable->adopted_list());
	}

	function view($id) # Show ALL updates for same adoptable.
	{
		$this->AdoptionStory->id  = $id;
		$adoptable_id  = $this->AdoptionStory->field("adoptable_id");
		if(empty($adoptable_id))
		{
			return $this->setError("Could not find adoptable details", array('action'=>'index'));
		}
		$this->redirect(array('controller'=>'adoptables','action'=>'success_story',$adoptable_id));
	}

}
