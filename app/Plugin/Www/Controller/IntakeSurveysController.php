<?
App::uses("WwwAppController", "Www.Controller");

class IntakeSurveysController extends WwwAppController
{
	var $uses = array('Www.IntakeSurvey');
	
	function manager_index()
	{
		$this->set("intakeSurveys", $this->IntakeSurvey->find('all'));
	}
	
	function manager_view($id)
	{
		$this->set("intakeSurvey", $this->IntakeSurvey->read(null,$id));
	}


	function add()
	{
		if(!empty($this->request->data))
		{
			if($this->IntakeSurvey->saveAll($this->request->data))
			{
				$this->sendManagerEmail("Website Inquiry", "Www.inquiry", array('intakeSurvey'=>$this->IntakeSurvey->read()));
				$this->setSuccess("Thanks for providing us with your website needs. We will contact you shortly if we have any questions.","/");
			} else {
				$this->setError("Sorry, we were unable to process your information. ".$this->IntakeSurvey->errorString());
			}
		}

		# XXX FIX! (or maybe cake is ok?)
		$this->set("species", $this->IntakeSurvey->getSetValues("species"));
		$this->set("homepageContents", $this->IntakeSurvey->getSetValues("homepage_content"));
		$this->set("adoptionPages", $this->IntakeSurvey->getSetValues("adoption_pages"));
		$this->set("fosterPages", $this->IntakeSurvey->getSetValues("foster_pages"));
		$this->set("volunteerPages", $this->IntakeSurvey->getSetValues("volunteer_pages"));
		$this->set("typesOfEmailMessages", $types=$this->IntakeSurvey->getSetValues("types_of_email_messages"));
		$this->set("basicPages", $types=$this->IntakeSurvey->getSetValues("basic_pages"));
		$this->set("donationFeatures", $types=$this->IntakeSurvey->getSetValues("donation_features"));
	}



}
