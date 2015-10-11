<nav class="navbar margin0 navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <? $logo = !empty($rescue['RescueLogo']['id']) ? $this->Html->image("/rescue_logos/image/{$rescue['RescueLogo']['id']}/x24",array('class'=>'left paddingright10')) : null; ?>
      <?= $this->Html->link($logo.$rescue['Rescue']['title'], array('plugin'=>null,'prefix'=>null,'controller'=>'rescues','action'=>'view'),array('class'=>'navbar-brand')); ?>
      </a>
    </div>
<div class="collapse navbar-collapse" id="header-navbar">
      <ul class="nav navbar-nav">
		<li class=''> <?= $this->Html->link("My Adoptables",array('plugin'=>null,'rescuer'=>1,'controller'=>'adoptables')); ?> </li>
		<!-- for adoptions, volunteers, fosters, etc. maybe as dropdown?
		<li class=''> <?= $this->Html->link("My Received Applications (*)",array('plugin'=>null,'controller'=>'applications')); ?> </li>
		-->
		<li class=''> <?= $this->Html->link("My News",array('plugin'=>null,'controller'=>'news_posts')); ?> </li>
		<li class=''> <?= $this->Html->link("My Events",array('plugin'=>null,'controller'=>'events')); ?> </li>
		<li class=''> <?= $this->Html->link("My Photos",array('plugin'=>null,'controller'=>'photo_albums')); ?> </li>
		<li class=''> <?= $this->Html->link("My Resources",array('plugin'=>null,'controller'=>'resources')); ?> </li>
      </ul>
      <? if($this->Rescue->dedicated()) { ?>
      	<?= $this->element("portal/login"); ?>
      <? } ?>
      <? if($this->request->params['controller'] == 'rescues' && $this->request->params['action'] == 'view') { # ONLY SHOW ON HOME PAGE ?>
      <ul class='nav navbar-right'>
      	<li>
		<div class='paddingright10'>
		<?= $this->Html->settings("Rescue Details",array('rescuer'=>1,'controller'=>'rescues','action'=>'edit'),array('class'=>'')); ?>
		</div>
	</li>
      </ul>
      <? } ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
