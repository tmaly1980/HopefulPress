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
		$this->set("species", $this->IntakeSurvey->dropdown("species"));
		$this->set("homepageContents", $this->IntakeSurvey->dropdown("homepage_content"));
		$this->set("adoptionPages", $this->IntakeSurvey->dropdown("adoption_pages"));
		$this->set("fosterPages", $this->IntakeSurvey->dropdown("foster_pages"));
		$this->set("volunteerPages", $this->IntakeSurvey->dropdown("volunteer_pages"));
		$this->set("typesOfEmailMessages", $types=$this->IntakeSurvey->dropdown("types_of_email_messages"));
		$this->set("basicPages", $types=$this->IntakeSurvey->dropdown("basic_pages"));
		$this->set("donationFeatures", $types=$this->IntakeSurvey->dropdown("donation_features"));
	}



}
