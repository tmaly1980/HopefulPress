<!-- THE goal of this survey form is to determine whether or not I have all the features people need, things people MIGHT want, the interest/usefulness of each feature I already DO have, as well as what I should plan for; and to slowly migrate into a do-it-yourself wizard -->
<?= $this->element("Core.js/colorpicker"); ?>
<? $id = !empty($this->request->data["IntakeSurvey"]["id"]) ? $this->request->data["IntakeSurvey"]["id"] : ""; ?>
<? $this->assign("page_title", "Website Inquiry"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<!-- MAYBE THIS CAN MORPH INTO SETUP SYSTEM -->

<div class="intakeSurveys form maxwidth900">
	<div  class='alert alert-info'>
		If you're interested in our rescue websites, please fill out the inquiry form with your details and we will get back to you as soon as possible.
	</div>

	<?php echo $this->Form->create('IntakeSurvey', array('role' => 'form','type'=>'file')); ?>
		<h3>Your Information</h3>
		<div class='row'>
			<?php echo $this->Form->input('first_name', array('required'=>1,'class' => '', 'label'=>null, 'Xplaceholder' => 'First Name','div'=>'col-md-6'));?>
			<?php echo $this->Form->input('last_name', array('required'=>1,'class' => '', 'label'=>null, 'Xplaceholder' => 'Last Name','div'=>'col-md-6'));?>
		</div>
		<div class='row'>
			<?php echo $this->Form->input('email', array('required'=>1,'class' => '', 'label'=>null, 'Xplaceholder' => 'Email','div'=>'col-md-6'));?>
			<?php echo $this->Form->input('organization', array('required'=>1,'class' => '', 'label'=>null, 'Xplaceholder' => 'Organization','div'=>'col-md-6'));?>
		</div>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('existing_website', array('class' => '', 'label'=>'Existing Website Address', 'Xplaceholder' => 'Existing Website')); ?>
			</div>
			<div  class='col-md-6'>
				<?php echo $this->Form->input('domain', array('class' => '', 'label' => 'Desired Website Address','tip'=>'your own domain (eg: lostdogrescue.org) or a subdomain of ours (eg: lostdog.hopefulpress.com)')); ?>
			</div>
		</div>
		<div class='row'>
			<?php echo $this->Form->input('existing_website_provider', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Existing Website Provider','div'=>'col-md-3')); ?>
			<?php echo $this->Form->input('existing_website_costs', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Existing Website Costs','div'=>'col-md-3'));?>
			<?php echo $this->Form->input('already_own_domain', array('class' => '', 'label' => 'I already own this domain','div'=>'col-md-6')); ?>
		</div>
		<div class='row'>
			<?php echo $this->Form->input('existing_website_details', array('class' => '', 'tip'=>"Tell us about your experience with your current website provider. What do you like/dislike?", 'Xplaceholder' => 'Existing Website Details','div'=>'col-md-12'));?>
		</div>
		<div class='row'>
			<div  class='col-md-6'>
			<?php echo $this->Form->input('facebook_page', array('class' => '', 'Xlabel'=>null, 'label' => 'Facebook Page, if applicable')); ?>
			<?php echo $this->Form->input('twitter_page', array('class' => '', 'Xlabel'=>null, 'label' => 'Twitter Page, if applicable')); ?>
			</div>
			<?php echo $this->Form->input('other_social_media_links', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Other Social Media Links','div'=>'col-md-6'));?>
		</div>

		<h3>About Your Rescue</h3>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('species', array('type'=>'select','multiple'=>'checkbox', 'label'=>null, 'Xplaceholder' => 'Species','div'=>'inline-checkbox'));?>
				<?php echo $this->Form->input('breeds', array('class' => '', 'label'=>'Specific breeds, if any')); ?>
			</div>
			<?php echo $this->Form->input('other_species', array('class' => '', 'label'=>'Other species,if applicable', 'Xplaceholder' => 'Other Species','div'=>'col-md-6'));?>
		</div>

		<h3>Content You Want On Your Website</h3>
			<?php echo $this->Form->input('basic_pages', array('type'=>'select','multiple'=>'checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Homepage Content','div'=>'inline-checkbox'));?>
			<?php echo $this->Form->input('homepage_content', array('type'=>'select','multiple'=>'checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Homepage Content','div'=>'inline-checkbox'));?>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('adoption_pages', array('type'=>'select','multiple'=>'checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Adoption Pages','div'=>'inline-checkbox'));?>
				<?php echo $this->Form->input('SampleAdoptionFormFile.file', array('class' => '', 'type'=>'file','label' => 'Sample Adoption Form (PDF,DOC,etc)'));?>
			</div>
			<?php echo $this->Form->input('other_adoption_pages', array('div'=>"col-md-6")); ?>
		</div>
		<div class='row'>
			<div class='col-md-6'>
			<?php echo $this->Form->input('foster_pages', array('div'=>"inline-checkbox",'type'=>'select','multiple'=>'checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Foster Pages'));?>
			<?php echo $this->Form->input('SampleFosterFormFile.file', array('type'=>'file','class' => '', 'label'=>null, 'label' => 'Sample Foster Form (PDF, DOC, etc)'));?>
			</div>
			<?php echo $this->Form->input('foster_page_details', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Foster Page Details','div'=>'col-md-6'));?>
		</div>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('volunteer_pages', array('div'=>"inline-checkbox",'type'=>'select','multiple'=>'checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Volunteer Pages'));?>
				<?php echo $this->Form->input('SampleVolunteerFormFile.file', array('type'=>'file','class' => '', 'label' => 'Sample Volunteer Form (PDF, DOC, etc)'));?>
			</div>
			<?php echo $this->Form->input('volunteer_page_details', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Volunteer Page Details','div'=>'col-md-6'));?>
		</div>
		<div  class='row'>
			<?php echo $this->Form->input('educational_pages', array('class' => '', 'label' => 'Educational Pages','div'=>'col-md-6','tip'=>'List ideas for any educational, informational, how-to and tips pages/articles'));?>
			<?php echo $this->Form->input('other_pages', array('class' => '', 'label'=>null, 'Xplaceholder' => 'Other Pages','div'=>'col-md-6','tip'=>'What other useful pages are not mentioned above?'));?>
		</div>

		<h3>Online Donations/Fundraising</h3>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('donation_features', array('type'=>'select','multiple'=>'checkbox','div'=>'inline-checkbox','class' => '', 'label'=>null, 'Xplaceholder' => 'Donation Features'));?>
				<?php echo $this->Form->input('current_donation_system', array('tip'=>'If you already are using a donation system, list it here')); ?>
			</div>
			<?php echo $this->Form->input('donation_details', array('tip'=>'List any other information about your online fundraising','div'=>'col-md-6')); ?>
		</div>

		<h3>Online Mailing List/Newsletter</h3>
		<div class='row'>
			<div class='col-md-6'>
				<?php echo $this->Form->input('want_mailing_list', array('class' => '', 'label'=>'Online Mailing List/Email Newsletter', 'Xplaceholder' => 'Want Mailing List'));?>
				<?php echo $this->Form->input('current_mailing_list_provider', array('class' => '', 'label' => 'Current Mailing List Provider, if any'));?>
				<?php echo $this->Form->input('current_total_subscribers', array('class' => '', 'label' => 'Current # Subscribers, if any'));?>
			</div>
			<div class='col-md-6'>
				<?php echo $this->Form->input('types_of_email_messages', array('type'=>'select','multiple'=>'checkbox','div'=>'inline-checkbox')); ?>
				<?php echo $this->Form->input('example_mailing_list_content', array('class' => '', 'label'=>'Other types of email content', 'Xplaceholder' => 'Example Mailing List Content','tip'=>'What other content do you expect to email to subscribers?')); ?>
			</div>
		</div>

		<h3>Website Design Details</h3>
		<div  class='alert alert-info'>
			Select a desired design from below. These designs are merely approximations and will use your specific content and images. If you have another design in mind, just skip this section and type in the URL below.
		</div>
			<?php echo $this->Form->hidden('design_name', array('id'=>'DesignName')); ?>
			<div id="Designs" class='row' style='height: 400px; overflow: scroll;'>
				<?= $this->element("Www.designs", array('selectable'=>true)); ?>
			</div>

			<?php echo $this->Form->input('other_design_url', array('class' => '', 'label'=>'Other Design URL','tip'=>'If you have another design in mind, provide the address here to a sample. We can re-create the design for your website.', 'Xplaceholder' => 'Other Design Url','id'=>'OtherDesign'));?>
		<div class='row'>
			<?php echo $this->Form->input('LogoFile.file', array('type'=>'file','class' => '', 'label'=>'Your Logo (PNG, JPG, PDF), if any', 'Xplaceholder' => 'Logo Id','div'=>'col-md-6'));?>
			<div class='col-md-6'>
				<label>Choose up to three colors for your website (optional)</label>
				<div class='row'>
					<?php echo $this->Form->input('color1', array('label'=>'Primary','class' => 'colorpicker','div'=>'col-md-4')); ?>
					<?php echo $this->Form->input('color2', array('label'=>'Secondary','class' => 'colorpicker','div'=>'col-md-4')); ?>
					<?php echo $this->Form->input('color3', array('label'=>'Third','class' => 'colorpicker','div'=>'col-md-4')); ?>
				</div>
			</div>
			<!-- XXX TODO change desired_colors to color1,  color2, color3 -->
		</div>
		<?= $this->Form->save("Send Website Inquiry",array('cancel'=>false));  ?>
	<?php echo $this->Form->end() ?>
	
	<script>
	$('.design a.btn').click(function() {
		var button = this;
		var container = $(button).closest('.design');
		var existing = $(container).hasClass('bg-success');

		$('.design a.btn').html('Select').removeClass('btn-danger').addClass('btn-success');
		$('.design').removeClass('bg-success');
		var name = '';
		if(!existing)
		{
			$(button).html('Selected').removeClass('btn-success').addClass('btn-danger');
			name  = $(container).addClass('bg-success').attr('id').replace(/^Design_/,"");
		}
		$('#DesignName').val(name);
	});
	/* THIS is causing erasing of design chosen from above.... 
	$('#OtherDesign').change(function() {
		$('#DesignName').val('');
	});
	*/
	</script>
	<style>
	</style>

	<div class='clear'></div>
	<script>
	</script>
</div>
