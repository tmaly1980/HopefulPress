<?php

class ShareHelper extends AppHelper {
	var $helpers = array('Html','Form');#'Hp','Publishable','Admin');

	var $ignore_plugins = array('support','stats'); # 'www'
	var $ignore_controllers = array('users');

	function friendly_url() # Nice public_url for this page....
	{
		# Take current url, deconstruct it, modify it, reconstruct it.
		#echo "P=".print_r($this->params,true);
		$full_url = $this->request->url;
		$params = Router::parse($full_url);

		# Strip out prefix. May want to keep named parameters if paginating.
		#unset($params['named']['status']); # Never show publically.
		if(!empty($params['prefix']))
		{
			$params['action'] = preg_replace("/^{$params['prefix']}_/", "", $params['action']);
			$params[$params['prefix']] = '';
			$params['prefix'] = false;
		}
		/*
		if($this->Form->singleton() && in_array($params['action'], array('v','admin_view','view')) && !empty($params['pass']))
		{
			# Drop id since singleton.
			unset($params['pass']);
		}
		if(in_array($params['action'], array('v','admin_view','view')) && !$this->Form->singleton())
		{
			# Try to get slug instead of ID.
			$idurl = $this->Form->fieldValue('idurl');
			if(!empty($idurl))
			{
				#echo "ID=$idurl";
				$params['pass'][0] = $idurl;
			}
		}
		*/

		if(!empty($params['pass']))
		{
			foreach($params['pass'] as $k=>$v)
			{
				$params[$k] = $v;
			}
			unset($params['pass']);
		}

		#if($params['action'] == 'view') { $params['action'] = 'v'; } # Shorten.

		# Avoid defaults.
		unset($params['url']); # .html added for some weird reason...
		#if(empty($params['pass'])) { $params['pass'] = array(); }
		#if(empty($params['named'])) { $params['named'] = array(); }

		#echo "UB=".print_r($params,true);
		$url = Router::url($params,true);
		#$url = Router::url($params,true); # Absolute url, w/hostname
		#echo "U=$url";
		return $url;
	}

	function share($large = true, $class='right_align medium black whitebg')
	{
		$prefix = !empty($this->params['prefix']) ? $this->params['prefix'] : null;
		if(Configure::read("members_only")) { return; }
		#if(!$this->Publishable->is_published()) { return; }
		# Not ready.

		$url = $this->friendly_url();
		$absurl = urlencode($this->Html->site_url($url));

		#if($this->getView()->layout != 'default') { return; }


		#if(!empty($this->params['manager'])) { return; } # WHATEVER
		if(!empty($this->params['plugin']) && in_array($this->params['plugin'], $this->ignore_plugins)) { return; }
		if(!empty($this->params['controller']) && in_array($this->params['controller'], $this->ignore_controllers)) { return; }

		$current_site = $this->getVar("current_site");
		$page_title = !empty($current_site['Site']['title']) ? $current_site['Site']['title'] : null;
		$title_for_layout = $this->getVar('title_for_layout');
		if(!empty($title_for_layout))
		{
			$page_title .= ": $title_for_layout";
		}
		# XXX should instead get title from html via js.


		# Use JS 

		# TODO EXCLUDE UNPUBLISHED

		ob_start();
		?>
		<script>
		// Get title via JS, so accurate title....
		(function($) { 
			$(document).ready(function() {
				$('.share').click(function(e) {
					var via = $(this).data('via');
					e.preventDefault();

					var url = window.location.href;
					var shareurl = '<?= Router::url(array($prefix=>null,'plugin'=>'sharable','controller'=>'share','action'=>'share')); ?>';
					shareurl += '/'+via+'?page_url='+url;
					if(page_title = $.trim($("head title").text()))
					{
						shareurl += "&page_title="+page_title;
					}
					
					if(via == 'email')
					{
						window.location.href = "mailto:?subject="+page_title+"&body="+url;
					} else if(via == 'copypaste') {
						$.dialog(shareurl);
					} else {
						var height = 450;
						var width=550;
						var top = ($(window).height()-height)/2 + 100;
						var left = ($(window).width()-width)/2;
						window.open(shareurl, via+'-share', 'height='+height+', width='+width+', top=' + top + ', left=' + left + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
					}
					return false;
				});

			});
		})(jQuery);
		</script>
		<div id="share" class='<?= $class ?> black whitebg'>
			<b>Share: </b>
			<? $shares = array('facebook','twitter','email'); ?>

			<? foreach($shares as $via) { ?>
				<?= $this->Html->link($this->Html->image(
					$large ? "/sharable/images/icons/$via.png":"/sharable/images/icons/small/$via.png", 
					array('title'=>"Share via ".ucwords($via),'class'=>'paddingsides2','align'=>'absmiddle')), "javascript:void(0)", array('e'=>0,'class'=>'share','data-via'=>$via)); ?>
			<? } ?>


			<?= $this->Html->link($this->Html->image(
				$large  ? "/sharable/images/icons/clipboard2.png" : "/sharable/images/icons/small/clipboard2.png", 
				array('title'=>'Copy/Paste URL','class'=>'paddingsides5','align'=>'absmiddle')), "javascript:void(0)",  array('e'=>0,'data-via'=>'copypaste', 'class'=>'share','title'=>'Copy/Paste URL')); ?>
		</div>
		<?
		# Set var so wont show up a second time.
		$this->_View->set("share", false);

		return ob_get_clean();
	}
}
