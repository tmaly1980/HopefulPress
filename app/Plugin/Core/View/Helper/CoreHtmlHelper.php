<?
App::uses('BoostCakeHtmlHelper','BoostCake.View/Helper');
class CoreHtmlHelper extends BoostCakeHtmlHelper 
{
	var $helpers = array('Core.GoogleMap','Text','Session','Multisite.Site');

	var $button_types = array( # function => icon
		'add'=>'plus',
		#'delete'=>'remove',
		'delete'=>'trash',
		'back'=>'chevron-left',
		'signin'=>'user',
		'signout'=>'log-out',
		'userlist'=>'th-list',
		'email'=>'envelope',
		'import'=>'list-alt',
		'settings'=>'cog',
		#'sortx'=>'move',
		#'edit'=>'edit', # redundant
	);

	var $og_images = array();

	function namedString($params = array()) # Convert to url friendly named string /a:b/c:d
	{
		$string = "";
		foreach($params as $k=>$v)
		{
			$string .= "/$k:$v";
		}
		return $string;
	}

	function linkb($type, $title, $url = array(), $opts = array(), $confirmMessage = false)
	{
		$opts['glyph_after'] = true;
		return $this->blink($type, $title, $url, $opts, $confirmMessage );
	}

	# Button-style link
	function blink($type, $title, $url = array(), $opts = array(), $confirmMessage = false)
	{
		if(is_string($opts)) { $opts = array('class'=>$opts); }
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= " btn  btn-default";
		if(!isset($opts['title'])) { $opts['title'] = $title; }

		if(preg_match("/^fa-/", $type))
		{
			$opts['fa'] = $type;
		} else {
			$opts['g'] = $type;
		}

		return $this->link($title, $url, $opts, $confirmMessage);
	}

	function linkg($type, $title, $url = array(), $opts = array(), $confirmMessage = false)
	{
		$opts['glyph_after'] =true;
		$opts['g'] = $type;
		return $this->link($title, $url, $opts, $confirmMessage);
	}

	# glyphicon link
	function glink($type, $title, $url = array(), $opts = array(), $confirmMessage = false)
	{
		$opts['g'] = $type;
		return $this->link($title, $url, $opts, $confirmMessage);
	}


	function droplink($title, $url = null, $opts = array())
	{
		if(!isset($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= 'dropdown-toggle';
		$opts['data-toggle'] = 'dropdown';
		$opts['role'] = 'button';
		$opts['aria-expanded'] = "false";
		$opts['escape'] = false;
		$url = '#'; # ALWAYS, click to drop (needed for mobile devices)
		return $this->link("$title <span class='caret'></span>", $url, $opts);
	}

	function caret() { return $this->span(null, "caret"); }
	function span($content=null,$class=null) { return "<span class='$class'>$content</span>"; }

	function link($title, $url = null, $opts = array(), $confirmMessage = null)
	{
		if(!isset($opts['escape'])) { $opts['escape'] = false; }
		if(!isset($opts['class'])) { $opts['class'] = ''; }

		if(!empty($opts['fa']))
		{
			$text = $title;
			$type = $opts['fa'];
			$icon = !empty($this->button_types[$type]) ? $this->button_types[$type] : $type;
			# DONT SHOW TEXT ON SMALL DEVICES < 768
			$text = !empty($text) ? "<span class='".((!isset($opts['short'])||$opts['short'])?"hidden-xs hidden-sm":"")."'> $title </span>" : null;
			$icon = "<i class='fa $icon'></i>";
			$title = (!empty($opts['glyph_after']) || !empty($opts['gafter'])) ? $text.$icon : $icon.$text;
		}

		if(!empty($opts['g']))
		{
			$text = $title;
			$type = $opts['g'];
			$icon = !empty($this->button_types[$type]) ? $this->button_types[$type] : $type;
			# DONT SHOW TEXT ON SMALL DEVICES < 768
			$text = !empty($text) ? "<span class='".((!isset($opts['short'])||$opts['short'])?"hidden-xs hidden-sm":"")."'> $title </span>" : null;
			$icon = "<span class='glyphicon glyphicon-$icon'></span>";
			$title = (!empty($opts['glyph_after']) || !empty($opts['gafter'])) ? $text.$icon : $icon.$text;
		}
		if(!empty($opts['update'])) # Backwards comptibility
		{
			$opts['data-update'] = $opts['update'];
			$opts['class'] .= ' json';
		}
		return parent::link($title, $url, $opts, $confirmMessage);
	}

	function link_toggler($items, $opts = array()) # Link buttons to multiple pages
	{
		$controller = $this->request->params['controller'];
		$id = !empty($opts['id']) ? $opts['id'] : rand(10000,99999);
		if(empty($opts['class'])) { $opts['class'] = ''; }

		ob_start();
		?>
		<div id='<?= $id ?>' class='btn-group <?= $opts['class'] ?>'>
			<? $i = 0; foreach($items as $item) { 
				$parsedUrl = Router::parse($item[0]);
			?>
			<a href='<?= $item[0] ?>' class='btn <?= $controller == $parsedUrl['controller'] ? "btn-primary" : "btn-default" ?>'?>
				<? if(!empty($item[2])) { ?>
					<i class='<?= $item[2] ?>'></i>
				<? } ?>
				<span class='<?= !empty($item[2]) ? "hidden-xs hidden-sm":""?>'>
					<?= $item[1]; ?>
				</span>
			</a>
			<? $i++; } ?>
		</div>
		<div class='clear'></div>
		<?
		return ob_get_clean();
	}

	function mobile_toggler($items, $opts = array())
	{ # Toggles multiple blocks on small/mobile devices
		# (label, id, icon)

		$id = !empty($opts['id']) ? $opts['id'] : rand(10000,99999);
		$bgid = "button-group-$id";
		if(empty($opts['class'])) { $opts['class'] = ''; }

		ob_start();
		?>
		<div id='<?= $bgid ?>' class='btn-group <?= $opts['class'] ?> visible-xs-block visible-sm-block' role='group'>
			<? $i = 0; foreach($items as $item) { ?>
			<button type='button' data-target='<?= $item[1] ?>' class='btn <?= $i > 0 ? "btn-default" : "btn-primary" ?>'>
				<span class='glyphicon glyphicon-<?= !empty($item[2]) ? $item[2] : $item[1]; ?>'></span> <span class='hidden-xs hidden-sm'><?= $item[0] ?></span>
			</button>
			<? $i++; } ?>
		</div>
		<script>
		$(document).ready(function() {
			<? for($i = 0; $i < count($items); $i++) { ?>
				$('#<?= $items[$i][1] ?>').addClass('togglable-<?= $id ?>');
				<? if($i > 0) { ?>
				$('#<?= $items[$i][1] ?>').addClass('hidden-xs hidden-sm');
				<? } ?>
			<? } ?>
			$('#<?= $bgid ?> button').click(function() {
				$('#<?= $bgid ?> button').removeClass('btn-primary').addClass('btn-default');
				$(this).addClass('btn-primary').removeClass('btn-default');
				var target = $(this).data('target');

				$('.togglable-<?= $id ?>').addClass('hidden-xs hidden-sm');
				$('#'+target).removeClass('hidden-xs hidden-sm');
			});
		});
		</script>
		<?
		return ob_get_clean();
	}

	function maplink($address)
	{
		$url = "http://maps.google.com/?q=$address&t=h";
		return $this->link($address, $url);
	}

	function map($address, $custom_opts = array()) # More sensible defaults, aspect ratio preserved (bootstrap responsive)
	{
		$default_opts = array(
			'width'=>'',
			'height'=>'',
			'zoom'=>16, # 1-20
			'type'=>'HYBRID',
			'class'=>'embed-responsive-item',
			'address'=>$address,
			'localize'=>false,
			'infoWindow'=>false,
			'marker'=>true,
			'markerIcon'=>"http://maps.google.com/mapfiles/marker.png",
			'markerTitle'=>'Location',
			'latitude'=>null,
			'longitude'=>null,
		);
		$opts = array_merge($default_opts, $custom_opts);
		ob_start();
		?>
		<?= $this->script('http://maps.google.com/maps/api/js?sensor=true',false); ?>
		<div class='embed-responsive embed-responsive-16by9'>
		<?= $this->GoogleMap->map($opts); ?>
		</div>
		<div class='clear'></div>
		<?
		return ob_get_clean();
	}

	function summary($content, $len = 200)
	{
		if(empty($content)) { return null; }
		# Need to strip HTML....
		$intro_lines = split("\n", trim(strip_tags($content)));
		$intro = !empty($intro_lines) ? strip_tags($intro_lines[0]) : null;

		return $this->Text->truncate($intro, $len); 
	}

	# XXX RETHINK...

	function admin_edit($label, $data = null)
	{
		$url = array('admin' => 1);
		return $this->edit($label, $url, $data);
		# EDIT function will call can_edit
	}

	function smadd($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn-xs';
		return $this->add($label, $url, $opts);
	}

	function madd($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn-sm';
		return $this->add($label, $url, $opts);
	}

	function __call($method, $params=array()) # Assume button with name as icon.
	{
		$label = !empty($params[0]) ? $params[0] : null;
		$url = !empty($params[1]) ? $params[1] : null;
		$opts = !empty($params[2]) ? $params[2] : array();
		#list($label,$url,$opts) = $params;

		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls ';
		if(!empty($url['manager'])) { $opts['class'] .= " btn-danger "; } # Special color, so obvious.
		else if(!preg_match("/btn-(danger|warning|default|success|info|primary|link)/", $opts['class'])) { $opts['class'] .= ' btn-warning'; }
		return $this->blink($method, $label, $url, $opts);
	}

	function add($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls ';
		if(!empty($url['manager'])) { $opts['class'] .= " btn-danger "; } # Special color, so obvious.
		else if(!preg_match("/btn-(danger|warning|default|success|info|primary|link)/", $opts['class'])) { $opts['class'] .= ' btn-warning'; }
		return $this->blink('add', $label, $url, $opts);
	}
	function edit($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls ';
		if(!empty($url['manager'])) { $opts['class'] .= " btn-danger "; } # Special color, so obvious.
		else if(!preg_match("/btn-(danger|warning|default|success|info|primary|link)/", $opts['class'])) { $opts['class'] .= ' btn-warning'; }

		return $this->blink('edit', $label, $url, $opts);
	}
	function settings($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls ';
		if(!preg_match("/btn-/", $opts['class'])) { $opts['class'] .= ' btn-primary'; }
		return $this->blink('settings', $label, $url, $opts);
	}
	function remove($label, $url, $opts = array(), $confirmMessage = false)
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls';
		if(!preg_match("/btn-(danger|warning|default|success|info|primary|link)/", $opts['class'])) { $opts['class'] .= ' btn-danger'; }
		return $this->blink('remove', $label, $url, $opts, $confirmMessage);
	}
	function delete($label, $url, $opts = array(), $confirmMessage = false)
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn controls';
		if(!preg_match("/btn-(danger|warning|default|success|info|primary|link)/", $opts['class'])) { $opts['class'] .= ' btn-danger'; }
		return $this->blink('delete', $label, $url, $opts, $confirmMessage);
	}
	function smdelete($label, $url, $opts = array(), $confirmMessage = false)
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn-xs';
		return $this->delete($label, $url, $opts, $confirmMessage);
	}
	function mdelete($label, $url, $opts = array(), $confirmMessage = false)
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' btn-sm';
		return $this->delete($label, $url, $opts, $confirmMessage);
	}

	function back($label, $url, $opts = array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		else if(!preg_match("/btn-/", $opts['class'])) { $opts['class'] .= ' btn-default'; }
		return $this->blink('back', $label, $url, $opts);
	}
	function titlelink($title, $url = array(), $attrs = array())
	{
		if(!isset($attrs['class'])) { $attrs['class'] = ''; }
		$attrs['class'] .= ' medium bold block';
		return $this->link($title, $url, $attrs);
	}

	function site_url($url) # Absolute url for sharing, etc.
	{
		return $this->url($url, true); # Hopefully accurate....
	}

	# Glyphs/placeholders/etc
	function g($name, $class = '')
	{
		return $this->s("glyphicon glyphicon-$name $class");
	}
	function s($class=null) {
		return "<span class='$class'></span>";
	}
	function fa($class=null) {
		return "<i class='fa fa-$class'></i>";
	}

	# Facebook thumbnails on link sharing.
	function og_image($url=null,$prepend=false,$only=false) # Should be given Router::url("/relpath", true) for ABSOLUTE
	{
		if(!empty($url))
		{
			if($prepend)
			{
				$this->og_images = array_merge(array($url),$this->og_images);
			} else {
				$this->og_images[] = $url;
			}
			return;
		} else {
			if($this->_View->fetch("og_image_single")) { $this->og_images = !empty($this->og_images) ? array(array_shift($this->og_images)) : array(); }

			ob_start();
			foreach($this->og_images as $og_image) { ?>
				<meta property="og:image" content="<?= $og_image ?>"/>
			<? }
			return ob_get_clean();
		}
	}

}
