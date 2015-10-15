<div class='index'>
	<? if(empty($explicitAdopters) && empty($otherAdopters)) { ?>
	<div class='alert alert-warning border'>
		No adoption applicants available. You can always just type in the owner's information if you have it.
		<br/>
		<br/>
		<script>$.dialogbuttons(['close']);</script>
	</div>
	<? } ?>
	<? if(!empty($explicitAdopters)) { ?>
	<h3>Specific Applicants</h3>
		<div  class='alert alert-info'>These applicants are specifically interested in this adoptable</div>
		<?= $this->element("../Adopters/list",array('adoptions'=>$explicitAdopters,'adoptable_id'=>$adoptable_id)); ?>
	<? } ?>
	<? if(!empty($otherAdopters)) { ?>
	<h3>Other Adoption Applicants</h3>
		<?= $this->element("../Adopters/list",array('adoptions'=>$otherAdopters,'adoptable_id'=>$adoptable_id)); ?>
	<? } ?>
</div>
