<? $controller = $this->request->params['controller']; ?>
<?  $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
<div>
    <div id='navbar' class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
	  <? if(!empty($themeSettings['navbar-brand'])) { ?>
	  	<? if(!empty($memberPage)) { ?>
	  		<?= $this->Html->link($current_site['Site']['title']. " Members", "/members", array('class'=>'navbar-brand '.($controller == 'members'?"selected":""))); ?>
		<? } else { ?>
	  		<?= $this->Html->link($current_site['Site']['title'], "/", array('class'=>'navbar-brand '.($controller == 'homepages'?"selected":""))); ?>
		<? } ?>
	  <? } ?>
        </div>
        <div class="collapse navbar-collapse sidebar-navbar-collapse">
          <ul class="nav navbar-nav">
	  	<?= $this->element("navbar",array('controller'=>$controller,'pid'=>$pid)); ?>
          </ul>
	  <div class='clear'></div>
        </div><!--/.nav-collapse -->
    </div>
</div>
