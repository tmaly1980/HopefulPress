<? $controller = $this->request->params['controller']; ?>
<? $action = $this->request->params['action']; ?>

<?  $pid = !empty($project['Project']['id']) ? $project['Project']['id'] : null; ?>
<div>
    <div id='navbar' class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse sidebar-navbar-collapse">
          <ul class="nav navbar-nav">
	            <!--<li class=""><a href="/">Home</a></li>-->
		<? if(!empty($nav['newsCount']) || !empty($nav['eventCount']) || !empty($nav['photoCount'])) { ?>
	  	<li class='dropdown toggle '>
			<a class='<?= in_array($controller, array('news_posts','events','photo_albums','photos','videos')) ? "selected" : "" ?>' href='javascript:void(0)'>Media Center <?= $this->Html->s("caret"); ?></a>
			<ul class='dropdown-menu'>
				<? if(!empty($nav['newsCount'])) { ?>
	            		<li><?= $this->Html->link("News", "/news"); ?></li>
				<? } ?>
				<? if(!empty($nav['eventCount'])) { ?>
	            		<li><?= $this->Html->link("Events", "/events"); ?></li>
				<? } ?>
				<? if(!empty($nav['photoCount'])) { ?>
	            		<li><?= $this->Html->link("Photos", "/photos"); ?></li>
				<? } ?>
				<!--
	            		<li><?= $this->Html->link("Videos", "/videos"); ?></li>
				-->
			</ul>
		</li>
		<? } ?>
		<? if(!empty($nav['adoptionEnabled'])) { ?>
		<li class='dropdown toggle '>
			<?= $this->Html->link("Adopt ".$this->Html->s("carent"), "javascript:void(0);"); ?>
			<ul  class='dropdown-menu'>
				<!--<li><?= $this->Html->link("Overview", '/adoption'); ?></li>-->
				<? if(!empty($nav['adoptableCount'])) { ?>
				<li><?= $this->Html->link("Current Adoptables", array('controller'=>'adoptables','rescue'=>$rescuename)); ?></li>
				<? } ?>
				<? if(!empty($nav['adoptableFormEnabled'])) { ?>
				<li><?= $this->Html->link("Adoption Form", '/adoption/form'); ?></li>
				<? } ?>
				<? if(!empty($nav['adoptionStoryCount'])) { ?>
				<li><?= $this->Html->link("Success Stories", '/adoption/stories'); ?></li>
				<?  } ?>
			</ul>
		</li>
		<?  } ?>
		<? if(!empty($nav['donationsEnabled'])) { ?>
		<li class=''>
			<?= $this->Html->link("Donate", "/donation"); ?>
		</li>
		<? } ?>
		<? if(!empty($nav['volunteerEnabled'])) { ?>
		<li class=''>
			<?= $this->Html->link("Volunteer", "/volunteer"); ?>
		</li>
		<? } ?>
		<? if(!empty($nav['fosterEnabled']) && $rescue) { ?>
		<li class=''>
			<?= $this->Html->link("Foster", "/foster"); ?>
		</li>
		<? } ?>
		<? if(!empty($nav['education_pages'])) { ?>
		<li class='dropdown'>
			<?= $this->Html->link("Education ".(!empty($nav['education_pages'])?$this->Html->s("caret"):""), "/rescue/education"); ?>
		    	<ul class='dropdown-menu' role='menu'>
				<? foreach($nav['education_pages'] as $tid=>$ttitle) { ?>
	            		<li>
					<?= $this->Html->link($ttitle, "/education/$tid", array('class'=>
						($controller == 'pages' && !empty($page) && ($nav['pageidurls'][$page['Page']['id']] == $tid || $page['Page']['parent_id'] == $tid) ? 'selected':''))); ?>
				</li>
				<? } ?>
			</ul>
		    <? } ?>
		</li>

		    <? if(!empty($nav['resourceCount'])) { # Should this be for each rescue, or should there be some central peer-contributed list for everyone? Does it vary? ?>
		    <li class=''>
		    	<?= $this->Html->link("Resources", "/resources"); ?>
		    </li>
		    <? } ?>
		    <? if(!empty($nav['aboutPage'])) { ?>
		    <li class=''>
		    		<?= $this->Html->link("About Us", array('controller'=>'rescues','action'=>'about','rescue'=>$rescuename),  array('class'=>($action=='about'?"selected":""))); ?>
	            </li>
		    <? } ?>
		    <? if(!empty($nav['aboutPage'])) { ?>
		    <li class=''>
		    			<?= $this->Html->link("Contact Us", array('controller'=>'rescues','action'=>'contact','rescue'=>$rescuename),  array('class'=>($action=='contact'?"selected":""))); ?>
	            </li>
		    <? } ?>
          </ul>
	  <div class='clear'></div>
        </div><!--/.nav-collapse -->
    </div>
</div>
