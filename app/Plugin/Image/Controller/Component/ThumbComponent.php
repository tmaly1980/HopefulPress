<?
# Our own homebrew thumbnailer. Much cleaner.
class ThumbComponent extends Component
{
	# For output.
	var $width = 150; # Default.
	var $height = 150;
	var $rotate = 0;
	var $crop = false; # true = exactly w&h, false = at or under w&h
	var $errors = array(); # ???

	var $fixed_vertical_center = true; # false means leave top aligned.

	function generateThumbnail($src,$dest)
	{
		#error_log("SRC=$src, W={$this->width}, H={$this->height}");

		if(!file_exists($src)) { 
			throw new Exception("Image thumbnail $src does not exist.");
			exit(0);
		}

		$img = new Imagick($src);
		if(!empty($this->rotate))
		{
			$img->rotateImage('none', $this->rotate);
		}

		$srcw = $img->getImageWidth();
		$srch = $img->getImageHeight();
		#list($srcw, $srch) = getimagesize($src);
		#error_log("SRCW=$srcw, $srch");
		$srcw2h = $srcw/$srch;

		# NEVER NEVER scale the image larger than the original
		
		if($srcw < $this->width) # Too small, don't enlarge.
		{
			#error_log("WIDTH TOO MUCH");
			$this->width = $srcw;
		}
		if($srch < $this->height) # Too small, don't enlarge.
		{
			#error_log("HEIGHT TOO MUCH");
			$this->height = $srch;
		}

		#error_log("W={$this->width}, H={$this->height}");

		# If we've only given one or the other, calc the other, for sizing
		if(!empty($this->width) && empty($this->height))
		{
			$this->height = $this->width / $srcw2h;
			#error_log("H2={$this->height}");
		} else if (!empty($this->height) && empty($this->width)) { 
			$this->width = $this->height * $srcw2h;
			#error_log("W2={$this->width}");
		}
		#error_log("W={$this->width}, H={$this->height}");

		if(!empty($this->coords)) # We've given exact coords to zoom/crop
		{
			list($x,$y,$w,$h) = $this->coords;
			$img->cropImage($w, $h, $x, $y);
		}

		if(!empty($this->crop))
		{
			# Scale image first. 
			$width = $this->width;
			$height = ceil($width * $srch / $srcw);
			if($height < $this->height) # Not big enough to crop.
			{
				$height = $this->height;
				$width = ceil($height * $srcw / $srch);
			}
			$img->scaleImage($width,$height,true);

			# If image is landscape, we have to shift center.
			# If normal width, as per scaled height is bigger than width suggested, get difference.
			$offx = $width > $this->width ? ($width-$this->width)/2 : 0;

			# 0 = keep top aligned, else, calc by middle of pic.
			$offy = !empty($this->fixed_vertical_center) && $height > $this->height ? ($height-$this->height)/2 : 0; # 0; # 0 means just keep top aligned.

		#	error_log("CREOPPIN TO={$this->width}, {$this->height}, $offx,$offy");
			$img->cropImage($this->width,$this->height,$offx,$offy);
		} else {
			if($srcw>$srch) { 
				$width = $this->width;
				$height = $this->width * $srch / $srcw; 
			} else {
				$height = $this->height;
				$width = $this->height * $srcw / $srch; 
			}
			#error_log("SCALE ($srcw, $srch) IMAGE ($dest) $width, $height");

			$img->scaleImage(ceil($width),ceil($height),true);
		}

		if(!$img->writeImage($dest))
		{
			$this->errors[] = "Unable to save thumbnail";
		} else {
			$this->errors = array();
			return true;
		}
	}
}
