<? $this->assign("page_title", "Search Adoption Database"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add Animal/Adoptable",array('user'=>1,'action'=>'add')); ?>
	<?= $this->Html->blink("list", "Bulk Import",array('user'=>1,'action'=>'import')); ?>
<? $this->end("title_controls"); ?>
<?
Configure::load("Rescue.breeds");
$breeds = Configure::read("Breeds");
$species = array_combine(array_keys($breeds),array_keys($breeds));
?>

<div class='form'>
	<?= $this->Form->create("Adoptable",array()); ?>
	<div class='row'>
		<?= $this->Form->input("name", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Name")); ?>
		<?= $this->Form->input("breed", array('div'=>'col-md-3','type'=>'select','options'=>$breeds,'empty'=>'- All breeds -','label'=>false)); ?>
		<?= $this->Form->input("microchip", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Microchip ID")); ?>
		<?= $this->Form->input("Owner.name", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Owner Name")); ?>

	</div>
	<div align='right'>
		<?= $this->Form->save("Search", array('cancel'=>false)); ?>
	</div>
	<?= $this->Form->end(); ?>

</div>

<div class='bold  medium'><?= $this->Paginator->counter(array('format'=>'{:count} animal(s) found')); ?></div>

<? if(!empty($adoptables)) { ?>
<div class='index'>
<table class='table table-striped'>
	<tr>
		<th>&nbsp;</th>
		<th><?= $this->Paginator->sort("name"); ?></th>
		<th><?= $this->Paginator->sort("species"); ?></th>
		<th><?= $this->Paginator->sort("breed"); ?></th>
		<th><?= $this->Paginator->sort("gender"); ?></th>
		<th><?= $this->Paginator->sort("birthdate","Age"); ?></th>
		<th><?= $this->Paginator->sort("status"); ?></th>
		<th><?= $this->Paginator->sort("microchip"); ?></th>
	</tr>
<? foreach($adoptables as $adoptable) { 
	$imgid = !empty($adoptable['Adoptable']['page_photo_id']) ? $adoptable['Adoptable']['page_photo_id'] : null;
?>
	<tr>
		<td>
			<?= $this->Html->link($this->Html->image(!empty($imgid)?"/page_photos/page_photos/thumb/$imgid/50x50":"/rescue/images/nophoto.png", array('class'=>'width50 border')), "/page_photos/page_photos/view/$imgid", array('class'=>'lightbox','title'=>$adoptable['Adoptable']['name']));  ?>
		</td>
		<td><?= $this->Html->link($adoptable['Adoptable']['name'], array('user'=>1,'action'=>'edit',$adoptable['Adoptable']['id']),array('class'=>'underline')); ?></td>
		<td><?= $adoptable['Adoptable']['species'] ?></td>
		<td><?= $adoptable['Adoptable']['breed'] ?>
			<?= !empty($adoptable['Adoptable']['mixed_breed']) ? " mix" : "" ?>
		</td>
		<td>
			<?= $gender = $adoptable['Adoptable']['gender'] ?><? if(!empty($gender)) { ?><?= !empty($adoptable['Adoptable']['neutered_spayed']) ? ($gender == 'Male' ? "/Neutered" : "/Spayed") : "" ?><? } ?>
		</td>
		<td>
			<?= !empty($adoptable['Adoptable']['birthdate']) ? $this->Time->age($adoptable['Adoptable']['birthdate']) : "" ?>
		</td>
		<td><?= $adoptable['Adoptable']['status'] ?></td>
		<td><?= $this->Html->link($adoptable['Adoptable']['microchip'], array('user'=>1,'action'=>'edit',$adoptable['Adoptable']['id'])); ?></td>
	</tr>
	<? } ?>
</table>
<? } ?>
</div>

