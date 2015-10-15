<? $this->assign("page_title", "Search Your Adoption Database"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->add("Add Animal/Adoptable",array('user'=>1,'action'=>'add')); ?>
	<?= $this->Html->blink("list", "Bulk Import",array('user'=>1,'action'=>'import')); ?>
<? $this->end("title_controls"); ?>
<? $breeds = $this->Rescue->breeds(); ?>

<div class='form'>
	<?= $this->Form->create("Adoptable",array()); ?>
	<div class='row'>
		<?= $this->Form->input("name", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Name")); ?>
		<?= $this->Form->input("breed", array('div'=>'col-md-3','type'=>'select','options'=>$breeds,'empty'=>'- All breeds -','label'=>false)); ?>
		<?= $this->Form->input("microchip", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Microchip ID")); ?>
		<?= $this->Form->input("Owner.full_name", array('div'=>'col-md-3','label'=>false,'placeholder'=>"Owner Name")); ?>

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
		<th><?= $this->Paginator->sort("Owner.full_name","Owner name"); ?></th>
	</tr>
<? foreach($adoptables as $adoptable) { 
	$imgid = !empty($adoptable['AdoptablePhoto']['id']) ? $adoptable['AdoptablePhoto']['id'] : null;
?>
	<tr>
		<td>
			<?= $this->Html->link($this->Html->image(!empty($imgid)?array('controller'=>'adoptable_photos','action'=>'thumb',$imgid,'50x50'):"/images/nophoto.png", array('class'=>'width50 border')), array('action'=>'view',$adoptable['Adoptable']['id']), array('class'=>'','title'=>$adoptable['Adoptable']['name']));  ?>
		</td>
		<td><?= $this->Html->link($adoptable['Adoptable']['name'], array('action'=>'view',$adoptable['Adoptable']['id']),array('class'=>'underline')); ?></td>
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
		<td><?= $adoptable['Owner']['full_name'] ?></td>
	</tr>
	<? } ?>
</table>
<? } ?>
</div>

