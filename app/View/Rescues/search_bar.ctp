<?
if(!empty($rescuename)) { # Already in one, just show browse all...
?>
<ul class='breadcrumb'>
	<li>
		<?= $this->Html->back("Browse All Rescues",array('plugin'=>null,'controller'=>'rescues','action'=>'search'),array('class'=>'btn btn-default')); ?>
	</li>
	<li class='bold'>
		<?= !empty($rescueCount)?$rescueCount:"No" ?> rescues nearby.
		<!-- add location, radius -->
		<!-- nearby me or nearby CURRENT rescue? OR ALL rescues? -->

		<? if($this->Html->user("rescuer") && !$this->Html->user("Rescue")) { ?>
			<?= $this->Html->add("Add my rescue",array('plugin'=>null,'controller'=>'rescues','action'=>'add')); ?>
		<? } ?>
	</li>
</ul>
<?
} else {# No rescue chosen yet.
	$browse = $this->Html->link("Browse All Rescues",array('controller'=>'rescues','action'=>'search'),array('class'=>'btn btn-default')); 
	$heading = "Find a local rescue: $rescueCount rescues nearby";
	$sortby=array('distance'=>'Closest','title_asc'=>'Alphabetical');

	$controls = empty($this->Html->user("Rescue.id")) ? "<b>Manage a rescue?</b> ".$this->Html->add("Create a FREE rescue account","/rescuer/rescues/add",array('class'=>'btn-primary')) : null;

	echo $this->element("portal/search_bar",array('model'=>'Rescue','sortby'=>$sortby, 'browse'=>$browse,'heading'=>$heading,'controls'=>$controls));
}
