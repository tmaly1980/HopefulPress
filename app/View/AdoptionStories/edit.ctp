<? $id = !empty($this->request->data["AdoptionStory"]["id"]) ? $this->request->data["AdoptionStory"]["id"] : null; ?>
<div class="pages form">
<?= $this->element("Core.js/editor"); ?>
<? $this->assign("page_title", ($id ? "Edit ":"Add ").(!empty($adoptable_name)?"{$adoptable_name}'s ":"")."Adoption Story"); ?>
<? $id = !empty($this->request->data["AdoptionStory"]["id"]) ? $this->request->data["AdoptionStory"]["id"] : ""; ?>
	<?php echo $this->Form->create('AdoptionStory', array('role' => 'form')); ?>

			<?php echo $this->Form->hidden('id', array('class' => '', 'label'=>null, 'placeholder' => 'Id'));?>

		<div class='row'>

		<div class='col-md-6'>
			<?= $this->Form->title('title',array('placeholder'=>'Add a story title...')); ?>
			<?= $this->Form->input("created", array('type'=>'text','label'=>'Date','class'=>'datepicker','default'=>date("m/d/Y")));?>
			<? if(!empty($adoptable_id)) { ?>
				<?= $this->Form->hidden("adoptable_id", array('value'=>$adoptable_id)); ?>
			<? } else  { 
				$adoptable_id = !empty($this->request->data["AdoptionStory"]["adoptable_id"]) ? $this->request->data["AdoptionStory"]["adoptable_id"] : null; 
			?>
			<div id='Adoptable'>
				<?= $this->requestAction("/user/rescue/adoptables/select/$adoptable_id",array('return')); ?>
			</div>
			<? } ?>
		</div>
		<div class='col-md-6'>
			<?= $this->element("PagePhotos.edit"); ?>
		</div>

		</div>

			<?= $this->Form->content(); ?>
		<table width='100%'><tr><td>
		<? if(!empty($id)) { ?>
			<?= $this->Html->delete("Delete Story", array("action"=>"delete",$this->data["AdoptionStory"]["id"]), array("confirm"=>"Are you sure you want to delete this story?")); ?>
		<? } ?>
		</td><td align="right">
			<?= $this->Form->save("Save Adoption Story",array('cancel'=>false)); ?>
		</td></tr></table>
	<?php echo $this->Form->end() ?>

	<div class='clear'></div>
	<script>
	</script>
</div>
