<?# Shared between generic signup and applications.

# STILL need to call Form->create() elsewhere...

$availabilities = array(
	'Weekends',
	'Weekdays',
	'Mornings',
	'Afternoon',
	'Evenings',
	'Varies'
);
$interests = array(
	'Volunteering',
	'Administration/Clerical',
	'Publicity (fliers, brochures, newsletters)',
	'Medical Team',
	'Fundraising/Events',
	'Grant Writing',
	'Transportation',
	'Adoptions/Consulting',
	'Adoptions/Adoption days',
	'Phones',
	'Other'
);
?>

<?= $this->Form->input("RescueVolunteer.data.over_18", array('div'=>'col-md-3','label'=>"I am over 18", "required"=>true, 'type'=>'select','options'=>$this->Form->yesno,'default'=>''));  ?>

<div class='inline-checkbox'>
<?= $this->Form->input("RescueVolunteer.availability",array('type'=>'checkbox','legend'=>'I am available (check all that apply):','options'=>array_combine($availabilities,$availabilities))); ?>
<?= $this->Form->input("RescueVolunteer.interests",array('type'=>'checkbox','legend'=>'I am interested in:','options'=>array_combine($interests,$interests))); ?>
</div>

<?= $this->Form->input("RescueVolunteer.experience",array('type'=>'textarea','label'=>'Prior experience with animal welfare (list name and location of organizations, if any)','rows'=>4)); ?>
