<?
App::uses("SingletonController", "Singleton.Controller");
class SiteDesignsController extends SingletonController
{
	function style($theme = 'default')
	{
		# Get site design parameters...
		$siteDesign = $this->SiteDesign->first(); # Read from db.
		$this->set("siteDesign", $siteDesign);
		if(!empty($siteDesign['SiteDesign'])) # Write vars to var space.
		{
			foreach($siteDesign['SiteDesign'] as $k=>$v) { $this->set($k,$v); }
		}
		foreach($this->request->params['named'] as $k=>$v) { $this->set($k,$v); } # Override (previewer)

		$this->set("theme", $theme); # Let us override from URL, ie if previewed.
		$this->response->type("css");
		$this->layout = false;#'none';
	}

	function admin_view($id = null)
	{
		if(empty($this->request->query['preview']))
		{
			$this->set("preview_wrapper",true);
			# Load admin, form, etc down until #main 
			$this->layout = 'Rescue.rescue'; # (Minus header, nav, etc)
		} else { # Update, load inside #main, including content, header, nav, etc
			$this->set("preview",true);
			$this->layout = 'preview';
		}
	}

	function admin_edit($id = null)
	{
		$this->edit(); # Process.
	}

}
	
