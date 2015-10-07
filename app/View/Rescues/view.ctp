<? $this->assign("page_title", $rescue['Rescue']['title']); ?>
<div class='index'>
	<?= $this->Form->create("Rescue"); ?>
	<?= $this->Form->hidden('id'); ?>
	<div class='row'>
	<div class="col-md-6">
		<div class='row'>
		<?= $this->Form->input("title", array('div'=>'col-md-8','label'=>'Rescue name','required'=>1)); ?>
		<div class='col-md-4'>
			<?= $this->element("PagePhotos.edit");  ?>
		</div>
		</div>
		<?= $this->Form->input_group("hostname", array('id'=>'RescueHostname','required'=>1,'before'=>"http://$default_domain/rescues/",'label'=>'Home page address (no spaces or special characters)')); ?>
                <script>
                        $('#RescueHostname').filter_input({regex: '[^a-zA-Z0-9_]+', replace: '-'});
                </script>


		<h3>Contact Information</h3>
		<div class='row'>
		<?= $this->Form->input("email",array('div'=>'col-md-6')); ?>
		<?= $this->Form->input("phone",array('div'=>'col-md-6')); ?>
		<?= $this->Form->input("address",array('div'=>'col-md-6')); ?>
		<?= $this->Form->input("address_2",array('div'=>'col-md-6','label'=>'Apt, Suite, etc')); ?>
		<?= $this->Form->input("city",array('div'=>'col-md-6','required'=>1)); ?>
		<?= $this->Form->input("state",array('div'=>'col-md-6','required'=>1)); ?>
		<?= $this->Form->input("country",array('div'=>'col-md-4')); ?>
		<?= $this->Form->input("zip_code",array('div'=>'col-md-4','required'=>1)); ?>
		<?= $this->Form->input("service_area",array('div'=>'col-md-4','type'=>'number','label'=>'Service Area (mi)','note'=>'How far away do you accept or adopt animals?')); ?>
		</div>
	</div>
	<div class='col-md-6'>

		<!-- brainless to update (ie at capacity, etc)! -->

		<?= $this->Form->input("about",array('label'=>'About your rescue','rows'=>5)); ?>
		<?= $this->Form->input("history",array('label'=>"Your rescue's experience/history",'rows'=>5)); ?>
		<?= $this->Form->input_group("facebook_url",array('id'=>'FacebookUrl','placeholder'=>'http://facebook.com/YourRescuePage','before'=>$this->Html->fa("facebook"),'label'=>"Facebook Page")); ?>
		<?= $this->Form->input_group("twitter_url",array('id'=>'TwitterUrl','placeholder'=>'http://twitter.com/YourRescuePage','before'=>$this->Html->fa("twitter"),'label'=>"Twitter Page")); ?>
		<script>
		$('#FacebookUrl').autoprepend('http:\/\/');
		$('#TwitterUrl').autoprepend('http:\/\/');
		</script>


		<h3>Species, Breeds &amp; Capacity</h3>
		specific species or breeds you mainly work with, plus how many you can care for at one time
		<div>
			<? foreach($species as $spec=>$specName) { ?>
				<?= $this->Form->radio("restrictions.species",array($spec=>$specName)); ?>
				<div style='display: none;'>
					<?= $this->Form->input("restrictions.capacity",array('note'=>'How many of this species/breed')); ?>
					something species as a whole, and/or breed specific.
					<?= $this->Form->input("restrictions.0.breed",array('options'=>$species,'type'=>'checkbox','class'=>'species')); ?>
					<?= $this->Form->input("restrictions.0.capacity",array('note'=>'How many of this species/breed')); ?>
				</div>
				<div class='alert alert-info'>
					Your current availability is determined by # of active listings and your capacity listed here. If you have animals
					under your care not listed on this site, change your capacity listed here to reflect that.
				</div>
				<!-- do we ask how many they have currently? will site automatically-->
			<? } ?>
			<div style='display: none;'>
			</div>
		</div>
	</div>
	</div>


		<?= $this->Form->save($id?"Update listing":"Create listing",array('cancel'=>$id)); ?>
	<?= $this->Form->end(); ?>
</div>
