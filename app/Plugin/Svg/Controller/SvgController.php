<?

class SvgController extends Controller
{
	var $uses = array();
	var $helpers = array();
	var $viewClass = 'Svg.Svg2png';
	var $stale = false; # Force reload.
		# Could be determined by seeing a source file has changed.

	# Each action/method is a different file, different image generated...

	# We can utilize different function names for more specific operations, so less parameters each.

	function bg_gradient($color1 = 'CCC',$color2 = 'FFF', $height = 1600) # Bit longer height...
	{
		$this->vgradient($color1, $color2, $height); 
	}

	function vgradient($color1 = 'CCC',$color2 = 'FFF', $height = 600)
	{
		$params = array(
			'style'=>'linear',
			'orient'=>'vertical',
			'width'=>5,
			'height'=>$height,
			'color1'=>$color1,
			'color2'=>$color2,
			'mid'=>50,
			'end'=>100
		);
		return $this->_gradient($params);
	}

	function hrgradient($color1 = 'CCC', $color2 = 'FFF', $width = 600, $start = 0, $mid = 50)
	{ # DUAL gradient.... 1 to 2 (mid) back to 1
		$this->request->params['named']['style'] = 'repeat';
		$this->request->params['named']['start'] = $start;
		$this->request->params['named']['mid'] = $mid;
		return $this->hgradient($color1, $color2, $width);
	}

	function hgradient($color1 = 'CCC',$color2 = 'FFF', $width = 600)
	{
		$params = array(
			'style'=>'linear',
			'orient'=>'horizontal',
			'height'=>5,
			'width'=>$width,
			'color1'=>$color1,
			'color2'=>$color2,
			'mid'=>50,
			'end'=>100
		);
		return $this->_gradient($params);
	}

	function gradient()
	{
		$params = !empty($this->params['named']) ? $this->params['named'] : array();

		$defaults = array(
			'style'=>'linear',
			'orient'=>'vertical',
			'width'=>10,
			'height'=>25,
			'color1'=>'CCC',
			'color2'=>'FFF',
			'mid'=>50,
			'end'=>100
		);
		$params = array_merge($defaults, $params);
		return $this->_gradient($params);

	}
	function _gradient($params = array())
	{
		# Override func params with passed named params... (another way to pass more details)
		foreach($this->request->params['named'] as $k=>$v) { $params[$k] = $v; }

		# Cant pass via #FFF, #ACC, etc colors because server interprets as anchor offsets.

		$this->set("orient", $params['orient']);
		$this->set("width", $params['width']);
		$this->set("height", $params['height']);

		# Mid-point (in %) determines where 2nd color (or midpoint color) shows up. defaults to 50%
		# End determines where 2nd color (or mid-point) ends, adds sharpness or more prominence of 2nd color

		$stops = array();

		# shading algorithm...
		if($params['style'] == 'repeat') # Dual linear.... c1 to c2 (mid) to c1
		{
			$start = !empty($params['start']) ? $params['start'] : 0;
			$end = !empty($params['end']) ? $params['end'] : 100 - $start;

			$mid = !empty($params['mid']) ? $params['mid'] : 50;
			$mid2 = !empty($params['mid2']) ? $params['mid2'] : 100 - $mid;
			$stops = array(
				array('opacity'=>1,'color'=>"#{$params['color1']}",'offset'=>"{$start}%"),
				array('opacity'=>1,'color'=>"#{$params['color2']}",'offset'=>"{$mid}%"),
				array('opacity'=>1,'color'=>"#{$params['color2']}",'offset'=>"{$mid2}%"), # Adding in a 3rd point makes mid color wider/sharper
				array('opacity'=>1,'color'=>"#{$params['color1']}",'offset'=>"{$end}%"),
			);
		} else { # $style == linear
			$color1_2 = $this->_mid_color($params['color1'], $params['color2']);
			# Here, we ought to be allowed to specify end point and have mid assume to be half of that.... 
			if(empty($params['end'])) { $params['end'] = 100; }
			if(empty($params['start'])) { $params['start'] = 0; }
			$stops = array(
				array('opacity'=>1,'color'=>"#{$params['color1']}",'offset'=>'0%'),
				array('opacity'=>1,'color'=>"#{$color1_2}",'offset'=>"{$params['mid']}%"),
				array('opacity'=>1,'color'=>"#{$params['color2']}",'offset'=>"{$params['end']}%"),
			);
		}

		$this->set("stops", $stops);

		return $this->render("gradient");
	}

	function trans_gradient($style = 'linear', $orient = 'vertical', $width = 200, $height = 50, $color1 = 'CCC', $mid = 50, $end = null)
	{
		$this->set("orient", $orient);
		$this->set("width", $width);
		$this->set("height", $height);

		# shading algorithm...
		if($style == 'repeat')
		{
			if(empty($end)) { $end = $mid; } 
			$stops = array(
				array('opacity'=>1,'color'=>"#{$color1}",'offset'=>'0%'),
				array('opacity'=>0,'color'=>"#{$color1}",'offset'=>"{$mid}%"),
				array('opacity'=>0,'color'=>"#{$color1}",'offset'=>"{$end}%"),
				array('opacity'=>1,'color'=>"#{$color1}",'offset'=>'100%'),
			);
		} else { # linear
			if(empty($end)) { $end = 100; }
			$stops = array(
				array('opacity'=>1,'color'=>"#{$color1}",'offset'=>"0%"),
				array('opacity'=>0.5,'color'=>"#{$color1}",'offset'=>"{$mid}%"),
				array('opacity'=>0,'color'=>"#{$color1}",'offset'=>"{$end}%"),
			);
		}
		$this->set("stops", $stops);
	}

	function _mid_color($c1, $c2)
	{
		list($c1r,$c1g,$c1b) = $this->_hex2rgb($c1);
		list($c2r,$c2g,$c2b) = $this->_hex2rgb($c2);
		$c12r = $c1r + ($c2r-$c1r)/2;
		$c12g = $c1g + ($c2g-$c1g)/2;
		$c12b = $c1b + ($c2b-$c1b)/2;

		return sprintf("%02x%02x%02x", $c12r, $c12g, $c12b);
	}

	function _hex2rgb($color)
	{
		if(strlen($color) == 6)
		{
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} else if (strlen($color) == 3) {
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return array();
		}

		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return array($r,$g,$b);
	}
	
}
