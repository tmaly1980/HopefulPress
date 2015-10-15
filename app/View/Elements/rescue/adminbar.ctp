<? $controller = $this->request->params['controller']; ?>
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
      <?= $this->Html->link($logo.$rescue['Rescue']['title'], array('plugin'=>null,'prefix'=>false,'controller'=>'rescues','action'=>'view'),array('class'=>'navbar-brand')); ?>
      </a>
    </div>
<div class="collapse navbar-collapse" id="header-navbar">
      <ul class="nav navbar-nav">
		<? $animalControllers = array('adoptables'); ?>
		<li class='<?= in_array($controller,$animalControllers)?"active":""?>'> <?= $this->Html->link("My Animals",array('plugin'=>null,'user'=>1,'controller'=>'adoptables','action'=>'index')); ?> </li>
		<? $peopleControllers = array('adoptions','rescue_fosters','rescue_volunteers'); ?>
		<li class='dropdown <?= in_array($controller,$peopleControllers) ? "active":"" ?>'> 
			<?= $this->Html->link("My People ".$this->Html->caret(),'javascript:void(0)',array('class'=>"dropdown-toggle",'data-toggle'=>'dropdown')); ?> 
			<ul class='dropdown-menu' role='menu'>
				<li class=''> <?= $this->Html->link("Adopters",array('plugin'=>null,'user'=>1,'controller'=>'adopters','action'=>'index')); ?> </li>
				<li class=''> <?= $this->Html->link("Fosters",array('plugin'=>null,'admin'=>1,'controller'=>'rescue_fosters','action'=>'index')); ?> </li>
				<li class=''> <?= $this->Html->link("Volunteers",array('plugin'=>null,'admin'=>1,'controller'=>'rescue_volunteers','action'=>'index')); ?> </li>
			</ul>
		</li>
		<? $updatesControllers = array('news_posts','events','photo_albums','photos'); ?>
		<li class='dropdown <?= in_array($controller,$updatesControllers)?"active":""?>'> 
			<?= $this->Html->link("My Updates ".$this->Html->caret(),'javascript:void(0)',array('class'=>"dropdown-toggle",'data-toggle'=>'dropdown')); ?> 
			<ul class='dropdown-menu' role='menu'>
				<li class=''> <?= $this->Html->link("News",array('prefix'=>false,'plugin'=>null,'controller'=>'news_posts','action'=>'index')); ?> </li>
				<li class=''> <?= $this->Html->link("Events",array('prefix'=>false,'plugin'=>null,'controller'=>'events','action'=>'index')); ?> </li>
				<li class=''> <?= $this->Html->link("Photos",array('prefix'=>false,'plugin'=>null,'controller'=>'photo_albums','action'=>'index')); ?> </li>
			</ul>
		</li>
		<? $pagesControllers = array('donations','adoption_page_indices','adoption_page_indices','volunteer_page_indices','resource_pages'); ?>
		<li class='dropdown <?= in_array($controller,$pagesControllers)?"active":""?>'> 
			<?= $this->Html->link("My Pages ".$this->Html->caret(),'javascript:void(0)',array('class'=>"dropdown-toggle",'data-toggle'=>'dropdown')); ?> 
			<ul class='dropdown-menu' role='menu'>
				<li class=''> <?= $this->Html->link("Adopt",array('prefix'=>false,'plugin'=>null,'controller'=>'adoption_page_indices','action'=>'index')); ?> </li>
				<li class=''> <?= $this->Html->link("Donate",array('prefix'=>false,'plugin'=>'donation','controller'=>'donations')); ?> </li>
				<li class=''> <?= $this->Html->link("Foster",array('prefix'=>false,'plugin'=>null,'controller'=>'foster_page_indices')); ?> </li>
				<li class=''> <?= $this->Html->link("Volunteer",array('prefix'=>false,'plugin'=>null,'controller'=>'volunteer_page_indices')); ?> </li>
				<li class=''> <?= $this->Html->link("Resources",array('prefix'=>false,'plugin'=>null,'controller'=>'resource_pages')); ?> </li>
			</ul>
		</li>
      </ul>
      <? if($this->Rescue->dedicated()) { ?>
      	<?= $this->element("portal/login"); ?>
      <? } ?>
      <? if($this->Rescue->admin()) { #request->params['controller'] == 'rescues' && $this->request->params['action'] == 'view') { # ONLY SHOW ON HOME PAGE ?>
      <ul class='nav navbar-right'>
      	<li>
		<div class='paddingright10'>
		<?= $this->Html->settings("Rescue Details",array('plugin'=>null,'admin'=>1,'controller'=>'rescues','action'=>'edit'),array('class'=>'')); ?>
		</div>
	</li>
      </ul>
      <? } ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
