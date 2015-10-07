<?php
App::import('View', 'View', false);

class Svg2pngView extends View {

	function __construct(&$controller) {
		$this->_passedVars[] = 'stale';

		parent::__construct($controller);
		$this->layout = null;
	}

	function render($action = null, $layout = null) {
		$filename = !empty($this->params['url']['url']) ? "/images/".$this->params['url']['url'].".png" : null;
		$abs_filename = APP."/Plugins/Svg/webroot/$filename";
		# Store in our own directory, now.

		if(empty($action)) { $action = $this->action; }

		$abs_view_filename = $this->_getViewFileName($action);
		# Src file

		error_log("A=$abs_filename, FILENAME=$filename, AVF=$abs_view_filename");

		if($this->stale || empty($filename) || !empty($_REQUEST['reload']) || !file_exists($abs_filename) || filemtime($abs_view_filename) > filemtime($abs_filename))
		{
			error_log("REFRESH");

			# To have it refresh instead of reuse existing rendered copies, we can just re-save the svg core file.

			$svg = parent::render($action, null); # Get generated svg.
			#$this->_debug_svg($svg);
			if(!empty($_REQUEST['debug']) || !empty($_REQUEST['raw']))
			{
				header("Content-Type: text/plain");
				print_r($svg);
				exit(0);
			}
			$img = $this->_svg2png($svg); # Convert svg text to png

			error_log("PNGED!");

			# Eventually save file...

			if(!empty($filename)) { # Save file...
				$dir = dirname($abs_filename);
				error_log("MKDIR $dir");
				if(!is_dir($dir)) { mkdir($dir, 0755, true); }
				$img->writeImage($abs_filename);
			}

			if(empty($filename) || !file_exists($abs_filename)) # Nowhere to save, or couldnt save.
			{
				error_log("CANT SAVE, PRINTING DIRECT ");
				if(!empty($this->request->params['requested']))
				{
					return (string)$img;
				} else {
					header("Content-Type: image/png");
					echo $img;
				}
				exit(0);
			}
		}

		error_log("GETTING FILE $abs_filename");

		$img_data = file_get_contents($abs_filename);

		if(!empty($this->request->params['requested']))
		{
			error_log("REQD");
			return $img_data;
		} else {
			error_log("RAW PRINTR");

			####$this->redirect($filename);
			# Don't redirect, just output! So url doesnt change...
			header("Content-Type: image/png");
			echo $img_data;
			exit(0);
		}
	}

	function _svg2png($svg) # ImageMagick MAGIC! At least for simple svg that we hand-code templates for.
	{
		$image = new Imagick();
		$image->readImageBlob($svg);
		$image->setImageFormat('png24');
		return $image;
	}

	function _debug_svg($svg)
	{
		$lines = explode("\n", $svg);
		foreach($lines as $line)
		{
			$line = preg_replace("/\t/", " ", $line);
			error_log("$line");
		}
	}
}
