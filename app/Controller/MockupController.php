<?
class MockupController extends AppController
{
	function view()
	{
		$this->render(join("/", func_get_args()));
	}

	function adopt_thanks()
	{
		$this->setSuccess("Thanks for contacting us about your adoption request. We will contact you shortly.");
		$this->redirect("/mockup/adopt/view");
	}

	function sponsor_thanks()
	{
		$this->setSuccess("Thanks for sponsoring Mr. Wiggles. Every penny helps!");
		$this->redirect("/mockup/adopt/view");
	}

	function foster_thanks()
	{
		$this->setSuccess("Thanks for contacting us about your foster request. We will contact you shortly.");
		$this->redirect("/mockup/adopt/view");
	}
}
