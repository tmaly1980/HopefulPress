<? $id = !empty($this->request->data['Rescue']['id']) ? $this->request->data['Rescue']['id'] : null; ?>
<? $this->assign("page_title", ($id?"Update your":"Add a")." rescue"); ?>
<div class='form'>
	<?= $this->Form->create("Rescue"); ?>
	<?= $this->Form->hidden('id'); ?>

<ul id='tabs' class='nav nav-pills'>
	<li class='active'> <a href='#intro' id=''>Overview</a> </li>
	<li class=''> <a href='#about' id=''>About You</a> </li>
	<li class=''> <a href='#contact' id=''>Contact Info</a> </li>
	<li class=''> <a href='#donations' id=''>Donations</a> </li>
	<li class=''> <a href='#mailinglist' id=''>Mailing List</a> </li>
	<!--
	<li class=''> <a href='#specialization' id=''>Specialization</a> </li>
	-->
	<li class=''> <a href='#sponsors' id=''>Affiliate/Sponsor Ads</a> </li>
	<li class=''> <a href='#design' id=''>Design/Theme</a> </li>
	<!--
	<li class=''> <a href='#account' id=''>Account</a> </li>
	-->
</ul>

<div class='tab-content lightgreybg padding25 border'>
<div id='intro' class='tab-pane active'>
	<div class='row'>
		<div class='col-md-2'>
			<?= $this->element("PagePhotos.edit",array('photoModel'=>'RescueLogo'));  ?>
		</div>
		<div class='col-md-10 row'>
			<div class='row'>
				<div class='col-md-6'>
					<?= $this->Form->input("title", array('label'=>'Rescue name','class'=>'large','required'=>1)); ?>
					<?= $this->Form->input("subtitle", array('label'=>'Slogan','class'=>'','note'=>'Appears underneath main page title/logo')); ?>
					<? if($id) { ?>
					<hr/>
					<div>
						<?= $this->element("../Rescues/rescuer_view_plan"); ?>
					</div>
					<? } ?>
				</div>
				<div class='col-md-6'>
					<? if($id) { ?>
						<!-- XXX TODO change hostname -->
						<!-- suggest upgrading for more options -->
					<? } else { ?>
						<?= $this->Form->input_group("hostname", array('id'=>'RescueHostname','required'=>1,'before'=>"http://$default_domain/rescues/",'label'=>'Home page address','readonly'=>$id)); ?>
						<div class='info'>
							You can always have a dedicated address such as <b>http://YourRescue.<?=$default_domain?></b> or <b>http://YourRescue.org</b> by upgrading at any time.
						</div>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
        <script>
        	$('#RescueHostname').filter_input({regex: '[^a-zA-Z0-9_]+', replace: '-'});
        </script>
</div>
<div id='about' class='tab-pane'>
	<div class='row'>
	<div class='col-md-6'>
		<h3>Home page banner</h3>
		<div class='alert alert-info'>
			Add a banner to show at the top of your home page.
		</div>
		<?= $this->element("PagePhotos.edit",array('photoModel'=>'PagePhoto'));  ?>
	</div>
	<div class='col-md-6'>
		<h3>About page photo</h3>
		<div class='alert alert-info'>
			Add a picture with members for your 'About Us' page
		</div>
		<?= $this->element("PagePhotos.edit",array('photoModel'=>'AboutPhoto'));  ?>
	</div>
	</div>
	<?= $this->Form->input("about",array('label'=>'Tell the world a bit about your rescue','rows'=>5)); ?>
	<?= $this->Form->input("history",array('label'=>"Your rescue's experience/history",'rows'=>5)); ?>
</div>
<div id='contact' class='tab-pane'>
	<h3>Contact Information</h3>

	<div class='row'>
	<?= $this->Form->input_group("facebook_url",array('div'=>'col-md-6','id'=>'FacebookUrl','placeholder'=>'http://facebook.com/YourRescuePage','before'=>$this->Html->fa("facebook"),'label'=>"Facebook Page")); ?>
	<?= $this->Form->input_group("twitter_url",array('div'=>'col-md-6','id'=>'TwitterUrl','placeholder'=>'http://twitter.com/YourRescuePage','before'=>$this->Html->fa("twitter"),'label'=>"Twitter Page")); ?>
	</div>

		<script>
		$('#FacebookUrl').autoprepend('http:\/\/');
		$('#TwitterUrl').autoprepend('http:\/\/');
		</script>

		<div class='row'>
		<?= $this->Form->input("email",array('div'=>'col-md-6')); ?>
		<?= $this->Form->input("phone",array('div'=>'col-md-6')); ?>
		</div>
		<div class='row'>
		<?= $this->Form->input("address",array('div'=>'col-md-6')); ?>
		<?= $this->Form->input("address_2",array('div'=>'col-md-6','label'=>'Apt, Suite, etc')); ?>
		</div>
		<div class='row'>
		<?= $this->Form->input("city",array('div'=>'col-md-4','required'=>1,'default'=>$this->Session->read("location.city"))); ?>
		<?= $this->Form->input("state",array('div'=>'col-md-4','required'=>1,'default'=>$this->Session->read("location.region_code"))); ?>
		<?= $this->Form->input("country",array('div'=>'col-md-4')); ?>
		</div>
		<?#= $this->Form->input("zip_code",array('div'=>'col-md-4','required'=>0)); ?>
		<?#= $this->Form->input("service_area",array('div'=>'col-md-4','type'=>'number','label'=>'Service Area (mi)','note'=>'How far away do you accept or adopt animals?')); ?>
</div>
<div id='donations' class='tab-pane'>
	<?= $this->Form->input_group("paypal_email",array('type'=>'email','size'=>30,'before'=>$this->Html->fa('gift'),'note'=>'To enable donations, please provide your PayPal email')); ?>
	<?= $this->Form->input("sponsor_label",array('size'=>24,'label'=>'Button text for sponsoring','default'=>'Sponsor Me','note'=>'When you enable sponsorship for an adoptable, they will click on a button with this wording')); ?>
</div>
<div id='mailinglist' class='tab-pane'>
	<?= $this->element("../Rescues/rescuer_edit_mailinglist"); ?>
</div>
<div id='specialization' class='tab-pane'>
	<?= $this->element("../Rescues/rescuer_edit_specialization"); ?>
</div>
<div id='sponsors' class='tab-pane'>
	<div class='border alert alert-info'>
		Add any ads or links you may have to affiliates or sponsors with proceeds to your rescue. These ads/links will be listed on the side column of your rescue page.
	</div>
	<?= $this->Form->input("sidebar_content",array('label'=>'Sponsor/Affiliate Ads','class'=>'editor')); ?>
</div>

<div id='design' class='tab-pane'>
	<div class='alert alert-info'>
		Choose your theme and colors below
	</div>
	<h3>Theme</h3>
	<? Configure::load("SiteDesigns"); $themes = Configure::read("SiteDesigns.themes"); ?>
	<?= $this->Form->thumbs("theme",array('options'=>$themes,'path'=>'/images/themes','BROKEN_EVENT_BYPASS_IMG_BAD_URL_Xlarge_path'=>'/images/themes/large')); ?>

	<h3>Colors</h3>
	<?= $this->Form->color("color1"); ?>
</div>

<div id='account'>
	<!-- billing, account level, domain, etc. -->
</div>


</div>


		<?= $this->Form->save($id?"Update":"Create"); ?>
	<?= $this->Form->end(); ?>
</div>
