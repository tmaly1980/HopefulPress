	  <? if(!empty($memberPage)) { ?>
	  	<li class='dropdown toggle hover'>
			<a class='<?= in_array($controller, array('news_posts','events','photo_albums','photos')) ? "selected" : "" ?>' href='javascript:void(0)'>Media Center <?= $this->Html->s("caret"); ?></a>
			<ul class='dropdown-menu'>
            			<li><a href="/members/news">News</a></li>
            			<li><a href="/members/events">Events</a></li>
            			<li><a href="/members/photos">Photos</a></li>
			</ul>
		</li>
	        <li><a href="/members/pages">Pages</a></li>
		    <li class='dropdown toggle hover'>
		    	<a class='<?= in_array($controller, array('link_pages','download_pages')) ? "selected" : "" ?>' href='javascript:void(0)'>Resources <?= $this->Html->s("caret"); ?></a>
		    	<ul class='dropdown-menu'>
	        		<li><a href="/members/links">Links</a></li>
	        		<li><a href="/members/downloads">Downloads</a></li>
			</ul>
		    </li>

	  <? } else if(!empty($pid)) { ?>
	  	<li class='dropdown toggle hover'>
			<a class='<?= in_array($controller, array('news_posts','events','photo_albums','photos')) ? "selected" : "" ?>' href='javascript:void(0)'>Media Center <?= $this->Html->s("caret"); ?></a>
			<ul class='dropdown-menu'>
            			<li><a href="/project/<?= $pid ?>/news">News</a></li>
            			<li><a href="/project/<?= $pid ?>/events">Events</a></li>
            			<li><a href="/project/<?= $pid ?>/photos">Photos</a></li>
			</ul>
		</li>
	        <li><a href="/project/<?= $pid ?>/pages">Pages</a></li>
		    <? if(!empty($project_nav['topics'])) { ?>
		    <li class='dropdown hover'><a class='<?= in_array($controller, array('pages')) ? "selected" : "" ?>' href='#'>Topics <?= $this->Html->g("menu-down","hidden-sm hidden-xs"); ?> </a>
		    	<ul class='dropdown-menu'>
				<? foreach($project_nav['topics'] as $tid=>$ttitle) { ?>
	            		<li class=''>
					<a id='topic_<?= $tid ?>' href="/page/<?= $tid ?>"><?= $ttitle ?>
					</a>
				</li>
				<? } ?>
			</ul>
		    <? } ?>
		    <li class='dropdown toggle hover'>
		    	<a class='<?= in_array($controller, array('link_pages','download_pages')) ? "selected" : "" ?>' href='javascript:void(0)'>Resources <?= $this->Html->s("caret"); ?></a>
		    	<ul class='dropdown-menu'>
	        		<li><a href="/project/<?= $pid ?>/links">Links</a></li>
	        		<li><a href="/project/<?= $pid ?>/downloads">Downloads</a></li>
			</ul>
		    </li>
	  <? } else { # Global nav ?>
	            <!--<li class=""><a href="/">Home</a></li>-->
	  	<li class='dropdown toggle hover'>
			<a class='<?= in_array($controller, array('news_posts','events','photo_albums','photos')) ? "selected" : "" ?>' href='javascript:void(0)'>Media Center <?= $this->Html->s("caret"); ?></a>
			<ul class='dropdown-menu'>
	            		<li><a href="/news">News</a></li>
	            		<li><a href="/events">Events</a></li>
	            		<li><a href="/photos">Photos</a></li>
			</ul>
		</li>
		    <? if(!empty($nav['projects'])) { ?>
	            <li class='dropdown hover'><a class='<?= in_array($controller, array('projects')) ? "selected" : "" ?>' href="/projects">Projects
				<?= $this->Html->s("caret"); ?>
		    	</a>
		    
		    	<? if(!empty($nav['projects'])) { ?>
		    	<ul class='dropdown-menu'>
				<? foreach($nav['projects'] as $pid=>$ptitle) {  ?>
	            		<li class=''>
					<a id='project_<?= $pid ?>' href="/project/<?= $pid ?>"><?= $ptitle ?></a>
				</li>
				<? } ?>
			</ul>
			<? } ?>
		    </li>
		    <? } ?>
		    <? if(!empty($nav['topics'])) { ?>
				<? foreach($nav['topics'] as $tid=>$ttitle) { 
					$subtopics = !empty($nav['subtopics'][$tid]) ? $nav['subtopics'][$tid] : null;
				?>
	            		<li class='<?= $subtopics ? "dropdown" : "" ?>'>
					<a class='<?= $controller == 'pages' && !empty($page) && ($nav['pageidurls'][$page['Page']['id']] == $tid || $page['Page']['parent_id'] == $tid) ? 'selected':'' ?>' id='topic_<?= $tid ?>' href="/page/<?= $tid ?>"><?= $ttitle ?>
						<? if(!empty($subtopics)) { ?>
						<?= $this->Html->s("caret"); ?>
						<? } ?>
					</a>
					<? if(!empty($subtopics)) { ?>
		    			<ul class='dropdown-menu' role='menu'>
						<? foreach($subtopics as $sid=>$stitle) { ?>
						<li>
							<?= $this->Html->link($stitle, "/page/$sid"); ?>
						</li>
						<? } ?>
					</ul>
					<? } ?>
				</li>
				<? } ?>
		    <? } ?>
		    <? if(!empty($nav['other_pages'])) { ?>
		    <li class='dropdown toggle hover'><a href='javascript:void(0)'>More information <?= $this->Html->s("caret"); ?></a>
		    	<ul class='dropdown-menu'>
				<? foreach($nav['other_pages'] as $tid=>$ttitle) { ?>
	            		<li class=''> <a id='topic_<?= $tid ?>' href="/page/<?= $tid ?>"><?= $ttitle ?></a> </li>
				<? } ?>
			</ul>
		    <? } ?>
		    <? if(!empty($nav['linkCount']) || !empty($nav['downloadCount'])) { ?>
		    <li class='dropdown toggle hover'>
		    	<a class='<?= in_array($controller, array('link_pages','download_pages')) ? "selected" : "" ?>' href='javascript:void(0)'>Resources <?= $this->Html->s("caret"); ?></a>
		    	<ul class='dropdown-menu'>
	            		<li><a href="/links"><?= !empty($nav['linkPage']) ? $nav['linkPage'] : 'Links' ?></a></li>
	            		<li><a href="/downloads"><?= !empty($nav['downloadPage']) ? $nav['downloadPage'] : 'Downloads' ?></a></li>
			</ul>
		    </li>
		    <? } ?>
		    <? if(!empty($nav['aboutPage']) || !empty($nav['contactPage'])) { ?>
		    <li class='dropdown toggle hover'>
		    	<a class='<?= in_array($controller, array('about_pages','contact_pages')) ? "selected" : "" ?>' href='javascript:void(0)'>About Us <?= $this->Html->s("caret"); ?></a>
		    	<ul class='dropdown-menu'>
		    	<? if(!empty($nav['aboutPage'])) { ?>
	            		<li><a href="/about">About Us</a></li>
			<? } ?>
		    	<? if(!empty($nav['contactPage'])) { ?>
	            		<li><a href="/contact">Contact Us</a></li>
			<? } ?>
			</ul>
		    </li>
		    <? } ?>
	  <? } ?>

