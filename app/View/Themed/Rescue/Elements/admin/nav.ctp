<? $rescue = !empty($current_site['Site']['rescue_enabled']); # Or sanctuary mode ?>
<? $plugin = !empty($this->request->params['plugin'])  ? $this->request->params['plugin'] : null; ?>
<? $prefix = !empty($this->request->params['prefix'])  ? $this->request->params['prefix'] : null; ?>
<? $controller = $this->request->params['controller']; ?>
<? $action = preg_replace("/^{$prefix}_/", "", $this->request->params['action']); ?>
<? $settingsControllers = array('sites','site_designs','users','stripe_billing','dns','mail','aliases','mail_users'); ?>
<? $updatesControllers = array('news_posts','events','photos','photo_albums','photos'); ?>
<? $pageControllers = array('homepages','about_pages','contact_pages','resources','resource_pages','about_page_bios','contacts'); ?>
<? $adoptionControllers = array('adoption_overviews','adoption_faqs','adoption_pages','adoption_downloads','adoption_stories'); ?>
<? $adoptableControllers = array_merge($adoptionControllers, array('adoptables','adoptable_forms')); ?>
<? $donationControllers = array('donation_pages','donations'); ?>

    <div class="navbar navbar-default navbar-inverse Xnavbar-fixed-top">
        <div class="">
          <ul class="nav navbar-nav">

	  <!-- # Only bother once more automated, >10 users
	  <li class='<?= in_array($controller, array('dashboard')) ? "active" : "" ?>'>
	        <a class='medium bold' href="/user/dashboard"><?= $this->Html->fa("globe"); ?> Overview</a>
	  </li>
	  -->


	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("globe"); ?> Pages</a>
		<ul class='dropdown-menu' style='<?= in_array($controller, $pageControllers) ? "display:block;":"" ?>'>
	 	    	<li class='<?= $controller == 'homepages' ? 'active' : '' ?>'><a href="/"><?= $this->Html->g("home"); ?> Home Page</a></li>
			<? if($this->Html->is_admin()) { ?>
	            	<li class='<?= in_array($controller,  array('about_pages','about_page_bios')) ? 'active' : '' ?>'><a href="/about"><?= $this->Html->fa("university"); ?> About Us</a></li>
	            	<li class='<?= in_array($controller,  array('contact_pages','contacts')) ? 'active' : '' ?>'><a href="/contact"><?= $this->Html->g("phone-alt"); ?> Contact Us</a></li>
			<? } ?>
	            	<li class='<?= in_array($controller, array('resource_pages', 'resources')) ? 'active' : '' ?>'><a href="/resources"><?= $this->Html->g("globe"); ?> Resources</a></li>
		</ul>

	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->fa("newspaper-o"); ?> Updates</a>
		<ul class='dropdown-menu' style='<?= in_array($controller, $updatesControllers) ? "display:block;":"" ?>'>

	            <li class='<?= $controller == 'news_posts' ? 'active' : '' ?>'><a href="/news"><?= $this->Html->fa("newspaper-o"); ?> News</a></li>
	            <li class='<?= $controller == 'events' ? 'active' : '' ?>'><a href="/events"><?= $this->Html->g("calendar"); ?> Events</a></li>
	            <li class='<?= in_array($controller,array('photos','photo_albums')) ? 'active' : '' ?>'><a href="/photos"><?= $this->Html->g("camera"); ?> Photos</a></li>
	            <!--
		    <li class='<?= $controller == 'videos' ? 'active' : '' ?>'><a href="/videos"><?= $this->Html->g("film"); ?> Videos</a></li>
		    -->

		    <!--
	            <li class='<?= $controller == 'pages' ? 'active' : '' ?>'><a href="<?=$prefix?>/pages"><?= $this->Html->g("file"); ?> Pages</a></li>
		    -->
	            <!--<li class='<?= $controller == 'download_pages' ? 'active' : '' ?>'><a href="<?=$prefix?>/downloads"><?= $this->Html->g("download-alt"); ?> Downloads</a></li>-->
		</ul>
	  </li>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->fa("paw"); ?> Animals </a>
		<ul class='dropdown-menu' style='<?= preg_match("/^adopt/", $controller) ? "display:block;":"" ?>'>
	  	<? if($rescue) { ?>
	            <li class='<?= in_array($controller, $adoptionControllers) ? 'active' : '' ?>'><a href="/adoption"><?= $this->Html->fa("file-text-o"); ?> Overview</a></li>
	            <li class='<?= $prefix != 'user'  && $controller == 'adoptables' ? 'active' : '' ?>'><a href="/adoption/adoptables"><?= $this->Html->fa("paw"); ?> Adoptables</a></li>
	            <li class='<?= $controller == 'adoptable_forms' ? 'active' : '' ?>'><a href="/adoption/form"><?= $this->Html->g("copy"); ?> Adoption Form</a></li>
	            <li class='<?= $controller == 'adoptions' ? 'active' : '' ?>'><a href="/user/adoption/requests"><?= $this->Html->fa("folder-open-o"); ?> Adoption Requests</a></li>
	            <li class='<?= $controller == 'adoption_stories' ? 'active' : '' ?>'><a href="/adoption/stories"><?= $this->Html->g("star-empty"); ?> Success Stories</a></li>
	            <li class='<?= $prefix == 'user' && $controller == 'adoptables' ? 'active' : '' ?>'><a href="/user/adoption/search"><?= $this->Html->g("search"); ?>Database &amp; Search</a></li>
		<? } else { # Sanctuary mode ?>
	            <li class='<?= in_array($controller, $adoptionControllers) ? 'active' : '' ?>'><a href="/sanctuary"><?= $this->Html->fa("file-text-o"); ?> Overview</a></li>
	            <li class='<?= $prefix != 'user'  && $controller == 'adoptables' ? 'active' : '' ?>'><a href="/sanctuary/animals"><?= $this->Html->fa("paw"); ?> Animal Profiles</a></li>
		<? } ?>
		</ul>
	  </li>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->fa("gift"); ?> Donations</a>
		<ul class='dropdown-menu' style='<?= in_array($controller, $donationControllers) ? "display:block;":"" ?>'>
	            <li class='<?= $controller == 'donation_pages' ? 'active' : '' ?>'><a href="/donation"><?= $this->Html->fa("gift"); ?> Overview/Wish List</a></li>
	            <li class='<?= $controller == 'donations' ? 'active' : '' ?>'><a href="/user/donation/donations"><?= $this->Html->g("piggy-bank"); ?> Donation History</a></li>
		</ul>
	  </li>
	  <? if($this->Html->is_admin()) { ?>
	  <li class='dropdown toggle <?= preg_match("/^volunteer/", $controller) ? "active" : "" ?>'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->fa("life-ring"); ?> Volunteer</a>
		<ul class='dropdown-menu' style='<?= preg_match("/^volunteer/", $controller) ? "display:block;":"" ?>'>
	            <li class='<?= $controller == 'volunteer_overviews' ? 'active' : '' ?>'><a href="/volunteer"><?= $this->Html->fa("life-ring"); ?> Overview </a></li>
	            <li class='<?= $controller == 'volunteers' ? 'active' : '' ?>'><a href="/admin/rescue/volunteers"><?= $this->Html->g("user"); ?> Volunteers/Applicants</a></li>
		</ul>
	  </li>
	  <? if($rescue) { ?>
	  <li class='dropdown toggle <?= preg_match("/^foster/", $controller) ? "active" : "" ?>'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->fa("heart"); ?> Foster</a>
		<ul class='dropdown-menu' style='<?= preg_match("/^foster/", $controller) ? "display:block;":"" ?>'>
	            <li class='<?= $controller == 'foster_overviews' ? 'active' : '' ?>'><a href="/foster"><?= $this->Html->fa("heart"); ?> Overview </a></li>
	            <li class='<?= $controller == 'fosters' ? 'active' : '' ?>'><a href="/admin/rescue/fosters"><?= $this->Html->g("user"); ?> Fosters/Applicants</a></li>
		</ul>
	  </li>
	  <? } ?>
	  <? } ?>
	  <li class='<?= in_array($controller, array('education_pages','education_overviews')) ? "active" : "" ?>'>
	        <a class='medium bold' href="/education"><?= $this->Html->fa("globe"); ?> Education</a>
	  </li>
	  <li class='dropdown toggle'>
	  <li class='<?= $plugin == 'mail' ? "active" : "" ?>'>
	  </li>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="/user/newsletter/subscribers"><?= $this->Html->g("envelope"); ?> Mailing List</a>
		<!--
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("send"); ?> Mailing List</a>
		<ul class='dropdown-menu' style='<?= $plugin == 'newsletter' ? "display:block;":"" ?>'>
	            <li class='<?= $controller == 'subscribers' ? 'active' : '' ?>'><a href="/user/newsletter/subscribers"><?= $this->Html->fa("file-text-o"); ?> Subscribers</a></li>
	            <li class='<?= $controller == 'newsletters' ? 'active' : '' ?>'><a href="/user/newsletter/messages"><?= $this->Html->fa("file-text-o"); ?> Messages</a></li>
	  	</ul>
		 -->
	  </li>
	<? if($this->Html->is_admin()) { ?>
	  <li class='dropdown toggle'>
	        <a class='medium bold' href="javascript:void(0)"><?= $this->Html->g("cog"); ?> Site Settings</a>
		<ul class='dropdown-menu' style='<?= in_array($controller, $settingsControllers) ? "display:block;":"" ?>'>
		        <li class='<?= $controller == 'dns' ? 'active' : '' ?>'><a href="/admin/dns"><?= $this->Html->g("globe"); ?> Website Address</a></li>
		        <li class='<?= $controller == 'stripe_billing' ? 'active' : '' ?>'><a href="/admin/billing"><?= $this->Html->fa("credit-card"); ?> Account/Billing</a></li>
		        <li class='<?= $controller == 'site_designs' ? 'active' : '' ?>'><a href="/admin/site_designs/view"><?= $this->Html->fa("paint-brush"); ?> Theme/Design</a></li>
		        <li class='<?= $controller == 'mail' ? 'active' : '' ?>'><a class='' href="/admin/mail"><?= $this->Html->g("envelope"); ?> Email Services</a></li>
			<!-- TODO added service, $15/mo -->

		        <li class='<?= $controller == 'users' ? 'active' : '' ?>'><a href="/admin/users"><?= $this->Html->fa("user-plus"); ?> Users</a></li>
		</ul>
	  </li>
	  <? } ?>

          </ul>
        </div><!--/.nav-collapse -->
    </div>
