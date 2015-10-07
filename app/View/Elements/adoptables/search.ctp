<?
Configure::load("Rescue.breeds");
$breeds = Configure::read("Breeds");
$species = array_combine(array_keys($breeds), array_keys($breeds));
?>
<div>
	<?= $this->Form->create("Adoptable"); ?>
	<div class='row'>
		<?= $this->Form->input("location",array('div'=>'col-md-3','placeholder'=>'Philadelphia, PA')); ?>
		<?= $this->Form->input("species",array('div'=>'col-md-3','options'=>$species)); ?>
		<?= $this->Form->input("breed",array('div'=>'col-md-3','type'=>'select','options'=>array())); ?>
		<?= $this->Form->save("Search",array('div'=>'col-md-3','cancel'=>false)); ?>
	</div>
	<?= $this->Form->end();  ?>
</div>

