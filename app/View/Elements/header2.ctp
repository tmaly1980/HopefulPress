    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><?= $current_site['Site']['title'] ?></a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <!--<li class=""><a href="/">Home</a></li>-->
            <li><a href="/news">News</a></li>
            <li><a href="/events">Events</a></li>
            <li><a href="/photos">Photos</a></li>
            <li><a href="/projects">Projects</a></li>
            <li><a href="/pages">Pages</a></li>
	    <? if(!empty($nav['topics'])) { ?>
	    <li class='dropdown'><a href='#'>Topics <span class='caret hidden-sm hidden-xs'></span> </a>
	    	<ul class='dropdown-menu'>
			<? foreach($nav['topics'] as $tid=>$ttitle) { 
				$subtopics = !empty($nav['subtopics'][$tid]) ? $nav['subtopics'][$tid] : null;
			?>
            		<li class='<?= $subtopics ? "dropdown" : "" ?>'>
				<a id='topic_<?= $tid ?>' href="/page/<?= $tid ?>"><?= $ttitle ?>
					<? /* if(!empty($subtopics)) { ?>
					<span class='caret hidden-sm hidden-xs'></span>
					<? } */ ?>
				</a>
				<? /* if(!empty($subtopics)) { ?>
	    			<ul class='dropdown-menu' role='menu'>
					<? foreach($subtopics as $sid=>$stitle) { ?>
						<?= $this->Html->link($stitle, "/page/$sid"); ?>
					<? } ?>
				</ul>
				<? } */ ?>
			</li>
			<? } ?>
		</ul>
	    <? } ?>
            <li><a href="/links"><?= !empty($nav['linkPage']) ? $nav['linkPage'] : 'Links' ?></a></li>
            <li><a href="/downloads"><?= !empty($nav['downloadPage']) ? $nav['downloadPage'] : 'Downloads' ?></a></li>
            <li><a href="/about"><?= !empty($nav['aboutPage']) ? $nav['aboutPage'] : 'About Page' ?></a></li>
            <li><a href="/contact"><?= !empty($nav['contactPage']) ? $nav['contactPage'] : 'Contact Us' ?></a></li>
            <li><a href="/admin/users">Users</a></li>
	    <? if(empty($current_user)) { ?>
	    <li><a href="/user/users/login">Sign In</a></li>
	    <? } else { ?>
            <li class='dropdown'>
	    	<a href='#'><?= $current_user['first_name'] ?> <span class='caret hidden-sm hidden-xs'></span></a>
		<ul class='dropdown-menu'>
	    		<li><a href="/user/users/account">Account</a></li>
	    		<li><a href="/user/users/logout">Sign Out</a></li>
		</ul>
	    </li>
	    <? } ?>
	    <!--
            <li class='dropdown'>
	    	<?= $this->Html->droplink("Sites", "#"); ?>
	    	<ul class='dropdown-menu' role='menu'>
	    		<li>
				<?= $this->Html->glink("list", "List sites", "/sites"); ?>
			</li>
			<li>
				<?= $this->Html->glink("add", "Add site", "/sites/add"); ?>
			</li>
		</ul>
	    </li>
            <li><a href="/users">Users</a></li>
            <li><a href="/pages">Pages</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#about">About</a></li>
	    -->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
