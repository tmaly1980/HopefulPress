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
		$url = Router::url($params);
		#$url = Router::url($params,true); # Absolute url, w/hostname
		#echo "U=$url";
		return $url;
	}

	function share($large = true, $class='right_align font12 black whitebg')
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
			$.fn.share = function(via, url)
			{
				var shareurl = '<?= Router::url(array($prefix=>null,'plugin'=>null,'controller'=>'sharable','action'=>'share')); ?>';
				shareurl += '/'+via+'?page_url='+url;
				if(page_title = $("head title").text())
				{
					shareurl += "&page_title="+page_title;
				}
	
				if(via == 'email')
				{
					$.dialog(shareurl);
				} else {
					window.location = shareurl;
				}
			};
		})(jQuery);
		</script>
		<div id="share" class='<?= $class ?> black whitebg'>
			<b>Share: </b>
			<? $shares = array('facebook','twitter','email_message'); ?>

			<? foreach($shares as $via) { ?>
				<?= $this->Html->link($this->Html->image(
					$large ? "/sharable/images/icons/$via.png":"/sharable/images/icons/small/$via.png", 
					array('title'=>"Share via ".ucwords($via),'class'=>'paddingsides2','align'=>'absmiddle')), "javascript:void(0)", array('e'=>0,'onClick'=>"$(this).share('$via', '$absurl');")); ?>
			<? } ?>


			<?= $this->Html->link($this->Html->image(
				$large  ? "/sharable/images/icons/clipboard2.png" : "/sharable/images/icons/small/clipboard2.png", 
				array('title'=>'Copy/Paste URL','class'=>'paddingsides5','align'=>'absmiddle')), array('plugin'=>'sharable','prefix'=>null,'controller'=>'sharable','action'=>'copypaste', '?'=>array('page_url'=>$url)), array('escape'=>false, 'title'=>"Copy/Paste",'class'=>'dialog')); ?>
		</div>
		<?
		# Set var so wont show up a second time.
		$this->_View->set("share", false);

		return ob_get_clean();
	}
}
