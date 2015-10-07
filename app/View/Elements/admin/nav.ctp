<?
$prefix = '';
if(!empty($memberPage)) { $prefix = "/members"; }
if(!empty($pid)) { $prefix = "/project/$pid"; }
?>
<? $controller = $this->request->params['controller']; ?>
<? $settingsControllers = array('sites','site_designs','users','stripe_billing'); ?>

<?  $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
    <div class="navbar navbar-default navbar-inverse Xnavbar-fixed-top">
        <div class="">
          <ul class="nav navbar-nav">

	  <? #if(!empty($project) || $controller == 'projects' || !empty($memberPage) || in_array($controller, $settingsControllers)) { ?>
	  <? if(!empty($project) || !empty($memberPage)) { ?>
	  <li>
	  	<a href="/" class=''><?= $this->Html->g("chevron-left") ?> Back to Main Website</a>
	  </li>
	  <li>
	  	<?= $this->Html->link(!empty($project) ? $project['Project']['title'] : $memberPage['MemberPage']['title'], $prefix, array('class'=>'medium bold')); ?>
	  </li>
	  <? } ?>

	  <? if(empty($project) && empty($memberPage)) { ?>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("globe"); ?> Pages</a>
		<ul class='dropdown-menu' style='<?= !in_array($controller, array_merge(array("member_pages"), $settingsControllers)) ? "display:block;":"" ?>'>
	 	    	<li class='<?= $controller == 'homepages' ? 'active' : '' ?>'><a href="/"><?= $this->Html->g("home"); ?> Home Page</a></li>
	  <? } ?>
	            <li class='<?= $controller == 'news_posts' ? 'active' : '' ?>'><a href="<?=$prefix?>/news"><?= $this->Html->fa("newspaper-o"); ?> News</a></li>
	            <li class='<?= $controller == 'events' ? 'active' : '' ?>'><a href="<?=$prefix?>/events"><?= $this->Html->g("calendar"); ?> Events</a></li>
	            <li class='<?= in_array($controller, array('photos','photo_albums')) ? 'active' : '' ?>'><a href="<?=$prefix?>/photos"><?= $this->Html->g("picture"); ?> Photos</a></li>
	            <li class='<?= $controller == 'pages' ? 'active' : '' ?>'><a href="<?=$prefix?>/pages"><?= $this->Html->g("file"); ?> Pages</a></li>
	            <li class='<?= $controller == 'link_pages' ? 'active' : '' ?>'><a href="<?=$prefix?>/links"><?= $this->Html->g("globe"); ?> Links</a></li>
	            <li class='<?= $controller == 'download_pages' ? 'active' : '' ?>'><a href="<?=$prefix?>/downloads"><?= $this->Html->g("download-alt"); ?> Downloads</a></li>
	  <? if(empty($project) && empty($memberPage)) { ?>
	            		<li class='<?= $controller == 'about_pages' ? 'active' : '' ?>'><a href="/about"><?= $this->Html->fa("university"); ?> About Us</a></li>
	            		<li class='<?= $controller == 'contact_pages' ? 'active' : '' ?>'><a href="/contact"><?= $this->Html->g("phone-alt"); ?> Contact Us</a></li>
		</ul>
	  </li>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("briefcase"); ?> Projects</a>
		<ul class='dropdown-menu'>
		  	<? foreach($nav['projects'] as $pid=>$ptitle) { ?>
			<li class='<?= (!empty($project) && $project['Project']['id'] == $pid) ? 'active' : '' ?>'>
				<a href='/project/<?= $pid ?>'><?= $ptitle ?></a>
			</li>
			<? } ?>
			<li class='<?= (!empty($project) && $project['Project']['id'] == $pid) ? 'active' : '' ?>'>
				<?= $this->Html->link($this->Html->g("plus")." Add a project", "/projects/add"); ?>
			</li>
		</ul>
	  </li>
	  <li class=''>
	        <a class='medium bold' href="/members"><?= $this->Html->g("lock"); ?> Members Only</a>
	  </li>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("cog"); ?> Site Settings</a>
		<ul class='dropdown-menu' style='<?= in_array($controller, $settingsControllers) ? "display:block;":"" ?>'>
		        <li class='<?= $controller == 'sites' ? 'active' : '' ?>'><a href="/admin/sites/view"><?= $this->Html->g("globe"); ?> Site Details</a></li>
		        <li class='<?= $controller == 'stripe_billing' ? 'active' : '' ?>'><a href="/admin/billing"><?= $this->Html->fa("credit-card"); ?> Billing</a></li>
		        <li class='<?= $controller == 'site_designs' ? 'active' : '' ?>'><a href="/admin/site_designs/view"><?= $this->Html->fa("paint-brush"); ?> Theme/Design</a></li>
		        <li class='<?= $controller == 'users' ? 'active' : '' ?>'><a href="/admin/users"><?= $this->Html->fa("user-plus"); ?> Users</a></li>
		</ul>
	  </li>
	  <? } ?>

	  <li class=''>
	  	<?= $this->Html->blink("life-ring", "HELP", "http://support.{$default_domain}/", array('target'=>'_new','title'=>"Get help, report a problem, ask a question",'class'=>'btn-warning medium bold')); ?>
	  </li>
	  <?/*

	  	<a href='Javascript:void(0);' class='large bold'>
			<? if (!empty($project)) { ?>
				<?= $this->Html->g("briefcase"); ?> <?= $project['Project']['title'] ?>
			<? } else if($controller == 'projects') { ?>
			<? } else if (!empty($memberPage)) { ?>
			<? } else if (in_array($controller, $settingsControllers)) { ?>
			<? } else { ?>
			<? } ?>
		<?= $this->Html->s("caret"); ?></a>
		<ul class='dropdown-menu'>
		</ul>
	  </li>
	  <? if($controller == 'projects' && empty($pid)) { ?>
	  <? } else { ?>
	  <? } ?>

	  */ ?>

          </ul>
        </div><!--/.nav-collapse -->
    </div>
