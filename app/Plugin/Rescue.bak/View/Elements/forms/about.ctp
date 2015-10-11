<?
if(!isset($title)){ $title = 'About You'; }
if(!isset($model)){ $model= $this->Form->defaultModel; }
if(!isset($required)) { $required = 1; }
?>
		<h3><?= $title?></h3>
		<div id=''>

		<div class='row'>
			<?= $this->Form->input("{$model}.first_name",array('required'=>$required,'label'=>'First name','div'=>'col-md-6')); ?>
			<?= $this->Form->input("{$model}.last_name",array('required'=>$required,'label'=>'Last name','div'=>'col-md-6')); ?>
		</div>

		<div class='row'>
			<?= $this->Form->input("{$model}.home_phone", array('required'=>$required,'div'=>'col-md-3')); ?>
			<?= $this->Form->input("{$model}.cell_phone", array('div'=>'col-md-3')); ?>
			<?= $this->Form->input("{$model}.work_phone", array('div'=>'col-md-3')); ?>
			<?= $this->Form->input("{$model}.best_time_to_call", array('div'=>'col-md-3')); ?>
			<?= $this->Form->input("{$model}.email", array('required'=>$required,'div'=>'col-md-6')); ?>
		</div>
		<div class='row'>
			<?= $this->Form->input("{$model}.address", array('required'=>$required,'label'=>'Street address','div'=>'col-md-6')); ?>
			<?= $this->Form->input("{$model}.address_2", array('label'=>'Apt #, Suite #, Unit # (optional)','div'=>'col-md-6')); ?>
			<?= $this->Form->input("{$model}.city", array('required'=>$required,'label'=>'City','div'=>'col-md-4')); ?>
			<?= $this->Form->input("{$model}.state", array('required'=>$required,'label'=>'State/Province','div'=>'col-md-5')); ?>
			<?= $this->Form->input("{$model}.zip_code", array('required'=>$required,'label'=>'Postal/ZIP Code','div'=>'col-md-3')); ?>
		</div>

		</div>

