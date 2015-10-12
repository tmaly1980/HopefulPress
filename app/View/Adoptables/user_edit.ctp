<? $animal = 'adoptable'; ?>
<? $this->assign("search_disabled",true); ?>
<? $id = !empty($this->request->data['Adoptable']['id']) ? $this->request->data['Adoptable']['id'] : null; ?>
<? $this->assign("page_title", $id?"Update Adoptable Details":"Add Adoptable Listing");# .(!empty($this->request->data['Adoptable']['name']) ? " &mdash;  ".$this->request->data['Adoptable']['name']:"")); ?>
<? $this->start("title_controls"); ?>
<? if(!empty($id)) { ?>
	<?= $this->Html->back("View profile", array('user'=>false,'action'=>'view','id'=>$id,'rescue'=>$rescuename)); ?>
	<?= $this->Html->delete("Delete Adoptable", array('action'=>'delete',$id,'rescue'=>$rescuename),array('confirm'=>"Are you sure you want to remove all records of this $animal? If this adoptable has just been adopted, you can always set the status to 'Adopted' instead.")); ?>
<? } ?>
<? $this->end("title_controls"); ?>
<div class='form'>
<?= $this->Form->create("Adoptable",null,array('validate'=>false)); ?>
<?= $this->Form->hidden("id"); ?>

<ul id='tabs' class='nav nav-pills'>
	<li class='active'> <a href='#intro' id=''>Overview</a> </li>
	<li class=''> <a href='#bio' id=''>Biography</a> </li>
	<li class=''> <a href='#pictures' id=''>More Pictures</a> </li>
	<li class=''> <a href='#sponsorship' id=''>Sponsorship</a> </li>
	<li class=''> <a href='#story' id=''>Success Story</a> </li>
	<li class=''> <a href='#owner' id=''>Microchip/Owner</a> </li>
</ul>

<div class='tab-content lightgreybg padding25 border'>
<div id='intro' class='tab-pane active row'>

	<div class='row'>
		<div class='col-sm-6'>
			<?= $this->Form->title("name",array('Xdiv'=>'col-md-12','placeholder'=>"Name of $animal",'required'=>1)); ?>
			<? if(empty($this->request->data['Adoptable']['adoptable_photo_id']) && !empty($this->request->data['Photos'][0]['id'])) { 
				$this->request->data['Adoptable']['adoptable_photo_id'] = $this->request->data['Photos'][0]['id'];
			} ?>
			<?= $this->element("PagePhotos.edit"); ?>
	
		</div>
		<div class='col-sm-6'>
			<div class='row'>
			<?= $this->Form->input("status",  array('label'=>false,'div'=>'col-xs-6','id'=>'Status','options'=>$statuses,'default'=>('Available'))); ?>
			<? $status = !empty($this->request->data['Adoptable']['status']) ? $this->request->data['Adoptable']['status'] : null; ?>
				<?= $this->Form->input("date_adopted",  array('div'=>array('id'=>'DateAdopted','style'=>($status!='Adopted'?"display:none;":""),'class'=>'col-xs-6'),'class'=>'StatusDate','Xdiv'=>'col-md-12','date'=>1));?>
			</div>
	
			<div class='row'>
				<?= $this->Form->input("species",array('div'=>'col-md-4','id'=>'AdoptableSpecies','options'=>$species,'empty'=>'Select')); ?>
				<?= $this->Form->input("breed",array('div'=>'col-md-4','id'=>'AdoptableBreed1','label'=>'Breed','empty'=>'Unknown','options'=>!empty($this->request->data['Adoptable']['breed'])?array($this->request->data['Adoptable']['breed']=>$this->request->data['Adoptable']['breed']):array())); ?>
				<div class='col-md-4'>
					<?= $this->Form->input("mixed_breed",array('div'=>'Xcol-md-2','id'=>'MixedBreed','label'=>'Mixed breed')); ?>
					<?= $this->Form->input("breed2",array('id'=>'AdoptableBreed2','div'=>array('id'=>"DivBreed2",'class'=>'Xcol-md-4'),'label'=>false,'Xlabel'=>'Secondary Breed','empty'=>'Unknown','options'=>!empty($this->request->data['Adoptable']['breed2'])?array($this->request->data['Adoptable']['breed2']=>$this->request->data['Adoptable']['breed2']):array())); ?>
				</div>
			</div>
			<div class='row'>
				<?= $this->Form->input("birthdate",array('div'=>'col-md-3','data-date-start-view'=>1,'date'=>1,'size'=>10,'tip'=>'Guess if unsure','placeholder'=>'mm/dd/yyyy')); # XXX TODO try asking for year so faster choice if older ?>
				<?= $this->Form->input("age_group",array('div'=>'col-md-3','options'=>$ageGroups,'default'=>'Adult')); ?>
				<?= $this->Form->input("gender",array('div'=>'col-md-3','options'=>$genders)); ?>
				<?= $this->Form->input("neutered_spayed",array('div'=>'col-md-3','label'=>'Neutered/Spayed','options'=>$this->Form->yesnoblank)); ?>
			</div>
			<div class='row'>
				<?= $this->Form->input_group("weight_lbs",array('div'=>'col-md-3','label'=>'Weight (Lbs)')); ?>
				<?= $this->Form->input("adult_size",array('div'=>'col-md-3','options'=>$adultSizes,'default'=>'Medium')); ?>
				<?= $this->Form->input("child_friendly",array('div'=>'col-md-3','options'=>$this->Form->yesnoblank)); ?>
				<?= $this->Form->input("minimum_child_age",array('div'=>'col-md-3','label'=>'Recom. age')); ?>
			</div>
			<div class='row'>
					<?= $this->Form->input("cat_friendly",array('div'=>'col-md-3','options'=>$this->Form->yesnoblank)); ?>
					<?= $this->Form->input("dog_friendly",array('div'=>'col-md-3','options'=>$this->Form->yesnoblank)); ?>
					<?= $this->Form->input_group("adoption_cost",array('id'=>'AdoptionCost','div'=>'col-md-6','before'=>'$','Xsize'=>5)); ?>
					<script>
					$('#AdoptionCost').filter_input({regex: "/[^0-9.]+$/"});
					</script>
	
					<?#= $this->Form->input("fostered",array('div'=>'col-md-3 align_right','label'=>'Currently Fostered')); ?>
					<?#= $this->Form->input("date_fosterable",  array('date'=>1));?>
			</div>
	
	
	
	
		</div>
	</div>

</div>
<div id='bio' class='tab-pane'>
	<?= $this->Form->input("biography", array('placeholder'=>"Add personal details about the $animal here...",'rows'=>6)); ?>
</div>

<div id='pictures' class='tab-pane'>
		<h3>More Pictures</h3>
		<? if($this->Html->me()) { ?>
		<div class=''>
			<?= $this->Form->fileupload("Upload.file", "Add photos...", array('id'=>'UploadFile','class'=>'controls')); ?>
			<?#= $this->Html->blink('move', "Resort", "javascript:void(0)", array('id'=>'sorter','class'=>'btn-primary white controls')); ?>
			<!-- XXX TODO figure out how to have automagic resort -->
		</div>
		<? } ?>
		<div class='clear'></div>
		<div class='border padding25 minheight50'>
			<?= $this->element("../AdoptablePhotos/list",array('adoptable'=>$this->request->data)); ?>
		</div>
</div>

<div id='sponsorship' class='tab-pane'>
	<div class='alert alert-info'>
		By enabling this animal's sponsorship, visitors will be able to donate to this animal's extraordinary needs via a 'Sponsor Me' button.
	</div>
	<? if(empty($nav['donationsEnabled'])) { ?>
	<div class='alert alert-warning'>
		Please add your PayPal email to your <?=$this->Html->link("rescue's account page",array('controller'=>'rescues','action'=>'edit','#'=>'donations')); ?> to enable sponsorships and donations.
	</div>
	<? } else { ?>
	<?= $this->Form->input("enable_sponsorship",array('id'=>"EnableSponsorship")); ?>
		<div id="SponsorshipDetails" style="<?= empty($this->request->data['Adoptable']['enable_sponsorship']) ? "display:none;":"" ?>">
			<?= $this->Form->input("sponsorship_details", array('label'=>'Specific Needs/Details','tip'=>"What are the extraordinary needs and expenses of this $animal?")); ?>
			<div class='alert alert-info'>
			These details will be listed on the donation page alongside the animal's main picture after someone clicks on 'Sponsor Me'.
			</div>
		</div>
	<? } ?>
</div>

<div id='story' class='tab-pane'>
	<div class='alert alert-info'>
		List post-adoption happy tails/success stories here.
	</div>
	<? /* if(empty($this->request->data['Adoptable']['status']) || $this->request->data['Adoptable']['status'] != 'Adopted') { ?>
	<div class='alert alert-info'>
		Make sure you set the status to 'Adopted' on the Overview tab.
	</div>
	<? } */ ?>

	<div class='row'>
		<div class='col-md-4'>
			<?= $this->element("PagePhotos.edit",array('modelClass'=>'Adoptable.SuccessStoryPhoto')); ?>
		</div>
		<div class='col-md-8'>
			<?= $this->Form->input("success_story",array('rows'=>5)); ?>
			<?= $this->Form->input("story_date",array('placeholder'=>'mm/dd/yyyy','type'=>'text','size'=>12,'class'=>'datepicker'));?>
		</div>
	</div>

</div>

<div id='owner' class='tab-pane'>
		<?= $this->Form->input("microchip",array('label'=>'Microchip #','Xdiv'=>'col-md-6')); ?>

		<? if(empty($this->request->data['Owner']['id']) && !empty($this->request->data['ProspectiveOwner'])) { # Show dropdown of potential owners... ?>
			<h3>Adoption Application(s)</h3>
			<? $adoptions = array(); 
			foreach($this->request->data['ProspectiveOwner'] as $owner)
			{
				$adoptions[] = array('Adoption'=>$owner);
			}
			?>
			<?= $this->element("Rescue.../Adoptions/list",array('adoptions'=>$adoptions)); ?>
		<? } # Even with prospective owners, STILL allow them to enter in a totally different owner.... ?>

		<?= $this->Form->hidden("Owner.id"); ?>

		<?= $this->element("forms/about",array('title'=>'Owner Information','model'=>'Owner','required'=>0));?>

		<?= $this->Form->input("Owner.status", array('label'=>'Adoption Status','note'=>"Make sure you change the adoptable's status as well",'default'=>'Adopted','options'=>$adoptionStatuses,'empty'=>' - ')); # Let stay pending if so... ?>
</div>

</div>

<script>
$('#EnableSponsorship').click(function() {
	$('#SponsorshipDetails').toggle();
});
</script>

<?= $this->Form->save($id?"Update Adoptable Profile":"Save Adoptable Profile",array('div'=>'right_align','cancel'=>false));#(!empty($id)?array('action'=>'view',$id):array('action'=>'index')))); ?>
<?= $this->Form->end(); ?>
</div>

<script>
$('#Status').change(function() {
	var status = $(this).val();
	if(status == 'Adopted')
	{
		$('#DateAdopted').show();
		$('#SuccessTab').css('display','');
	} else {
		$('#DateAdopted').hide();
		$('#SuccessTab').css('display','none');
	}
});
/* rescue may specialize in one species or even one breed, so we should simplify options!!! */
var breeds = <?= json_encode($breeds); ?>;
$('#AdoptableSpecies').change(function() {
	var species = $(this).val();
	var breedsForSpecies = breeds[species];
	$('#AdoptableBreed1').setoptions(breedsForSpecies,null,null,'Unknown');
	$('#AdoptableBreed2').setoptions(breedsForSpecies,null,null,'Unknown');
});
// may need to be 'click' instead... but dont want click fired on load to toggle checkmark
//$('#MixedBreed').click(function() { $(this).trigger('change'); });
$('#MixedBreed').change(function() {
	$('#DivBreed2').toggle($(this).is(":checked"));
});
$(document).ready(function() {
	$('#AdoptableSpecies').change();
	$('#MixedBreed').change();
});
</script>