<?/* DONT USE
class ABView extends View
{
	# AB Testing
	function render($view=null,$layout=null) 
	{
		if($variant = $this->ab_variant($view))
		{
			$view = $variant;
		}

		return parent::render($view);
	}

	/*
	Determining which one is "more effective".... What is the end result we're testing against?
	Maybe we can define in config file? (equally as easy as modifying view files)
	CALL TO ACTION:
		{ model: Site } // record created with marketing_visit_id matching
		{ page: array("/pages/features","/pages/signup") } // one or more pages to check effective click thru (ie could have changed labels)
	*/

	# This can be  modified to check more than just a,b variants. Turning into single function.
	function ab_variant($view) # Check if variant file exists... Assumes no theme and file in standard location.
	{
		$plugin = $this->request->params['plugin'];
		$controller = $this->request->params['controller'];

		# To give test results a bit more meaning, we can name whatever we want.... ie home.screenshot.ctp , home.photo.ctp
		$variantPrefix = "View".DS.Inflector::camelize($this->request->params['controller']).DS.$view;

		if(!empty($this->request->params['plugin']))
		{
			$variantPath = APP."Plugin".DS.Inflector::camelize($this->request->params['plugin']).DS.$variantPrefix;
		} else {
			$variantPath = APP.$variantPrefix;
		}

		error_log("VARIANT_PATH=$variantPath");

		$variant = !empty($this->request->query['variant']) ? $this->request->query['variant'] : # Let them be explicit!
			$this->Session->read("ABView.$plugin.$controller.$view");

		error_log("SESSION $plugin.$controller.$view=$variant");

		if(!empty($variant) && !file_exists("$variantPath.$variant.ctp")) # Invalid, removed, done testing.
		{
			$variant = null;
			$this->Session->delete("ABView.$plugin.$controller.$view");
		}

		if(!$variant && ($files = glob("$variantPath.*.ctp")) && !empty($files)) { # Variants exist but none chosen yet.
			error_log("VARIANT FILES FOUND=".print_r($files,true));

			$ix = rand(0,count($files)-1);
			error_log("IX=$ix");
			$file = $files[$ix];
			$file_parts = split("[.]", $file);
			error_log("FILE_PARTS=".print_r($file_parts,true));
			$ext = array_pop($file_parts);
			$variant = array_pop($file_parts);

			error_log("FILE=$file, VARIANT=$variant");

			$this->Session->write("ABView.$plugin.$controller.$view",$variant);

			# Now we need to tell Tracker.shutdown to save this...
		}

		if(!empty($variant))
		{
			error_log("VARIANT USING=$view.$variant");
			$view = "$view.$variant";
			# Record which one was chosen
			return $view;
		}

		return false;
	}
}
*/?>
