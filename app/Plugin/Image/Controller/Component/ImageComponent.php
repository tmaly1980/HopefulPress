<?
class ImageComponent extends Component
{
	var $components = array('Image.Thumb');
	# FOR RENDERING PHOTOS AT A SCALED SIZE.

	var $coords = false;

	function coords($x,$y,$w,$h)
	{
		$this->coords = array($x,$y,$w,$h);
	}

	# Cropped  means ....  ?
	function render($data, $width = null, $height = null, $cropped = false)
	{
		#error_log("RENDERING $width, $height");
		$path = $data['path'];
		$filename = $data['filename'];
		$mime = $data['type'];
		$rotate = !empty($data['rotate']) ? $data['rotate'] : 0;
		$modified = !empty($data['modified']) ? $data['modified'] : null;

		$abspath = $absmasterpath = APP."/webroot/$path";
		$absfile = $absmasterfile = "$abspath/$filename";

		#error_log("CROPPED=$cropped");

		if(!is_file($absmasterfile))
		{
			error_log("File Not Found: $absmasterfile, DATA=".print_r($data,true));
			throw new NotFoundException("File was not found");
			exit(0);
		}

		list($mw,$mh) = getimagesize($absmasterfile);
		$w2h = $mw/$mh;

		#error_log("PASSED W=$width, H=$height; FOUND w=$mw, h=$mh ($absmasterfile)");


		if(empty($width) && empty($height)) # NEITHER! keep 100%
		{
			$width = $mw;
			$height = $mh;
		}

		# Let us omit width or height...
		if(!empty($width) && empty($height))
		{
			$height = $width / $w2h;
		}

		if(!empty($height) && empty($width))
		{
			$width = $height * $w2h;
		}


		if(!empty($width) && !empty($height)) {
			$abspath = $cropped ? 
				"$absmasterpath/cropped/{$width}x{$height}" :
				"$absmasterpath/{$width}x{$height}";
		}

		# CROP FIRST, if desired...
		# as per the scaling (when cropping), do we ignore? dont specify?
		# do we save to disk with a different filename (containing coords)?
		if(!empty($this->coords))
		{
			list($x,$y,$w,$h) = $this->coords; # We need ALL FOUR coords!
			$abspath = "$abspath/{$x}x{$y}x{$w}x{$h}";

			$this->Thumb->coords = $this->coords;

		}
		$absfile = "$abspath/$filename";

		#error_log("ABSFILE=$absfile");

		if(!file_exists($abspath))
		{
			mkdir($abspath, 0755, true); # Recursive.
		}


		if($cropped) # Not based on image proportions.
		{
			$w2h = $width/$height;
		}


		# See if we need to shrink the picture to fit the constraints.
		$scaled_width = $width;
		$scaled_height = $scaled_width / $w2h;

		#error_log("prescale $width, $height; SCALE $scaled_height > $height ; $scaled_width > $width, W2h = $w2h");

		if($scaled_height > $height)
		{
			$scaled_height = $height;
			$width = $scaled_height * $w2h;
		}
		if($scaled_width > $width)
		{
			$scaled_width = $width;
			$height = $scaled_width / $w2h;
		}
		#error_log("postscale $width, $height");

		# XXX be careful about referencing absfile before it might exist! will crash image and cause broken pic on first time!

		# XXX in order to properly cache, we need to have a sane path....
		# ie one for all objects, ie a central controller.
		if(!empty($_REQUEST['rand']) || !empty($_REQUEST['clear']) || !empty($_REQUEST['reset']) || !empty($_REQUEST['refresh']) || !empty($_REQUEST['reload'])
			|| !file_exists($absfile) || ($absfile != $absmasterfile && filectime($absfile) < filectime($absmasterfile)) || (filemtime(__FILE__) > filemtime($absfile)) 
			|| (!empty($modified) && filemtime($absfile) < strtotime($modified))
			) # Updated.
		{
			#error_log("REGENERATING");
			#error_log("GEN $absmasterfile => $absfile ($width x $height)");
			$this->Thumb->crop = $cropped;
			$this->Thumb->width = $width;
			$this->Thumb->height = $height;
			$this->Thumb->rotate = $rotate;
			$this->Thumb->generateThumbnail($absmasterfile, $absfile);
			#error_log(filesize($absfile));
			#error_log("SAVING from $absmasterfile TO $absfile, C=$cropped, W=$width, H+$height");
		}

		#error_log("RETURNING $absfile");

		#error_log("REND");

		# Now render.

		if(!empty($this->request->params['requested']))
		{
			#error_log("REQ!");
			return file_get_contents($absfile);
		} else {
			#error_log("RAW PRINT!");
			if(!empty($_REQUEST['debug'])) { 
				header("Content-Type: text/plain");
			} else {
				header("Content-Type: $mime");
			}
			echo file_get_contents($absfile);
			exit(0);
		}
	}


}
