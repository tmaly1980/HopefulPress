<? $this->assign("page_title", "Import Adoptables"); ?>
<div class="reports form container-fluid maxwidth600 border lightgreybg">
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All Adoptables", array('action'=>'index')); ?>
<? $this->end(); ?>

<?php echo $this->Form->create('Adoptable',array('type'=>'file')); ?>
	<h4>Provide a comma-separated (CSV) spreadsheet file</h4>
	<div class='right'>
		<?= $this->Html->link("Sample CSV Template",array('user'=>1,'controller'=>'adoptables','action'=>'import_template')); ?>
	</div>
	
	<?= $this->Form->input('file',array('type'=>'file','label'=>false)); ?>
	<?= $this->Form->save('Upload',array('cancel'=>false)); ?>
<?php echo $this->Form->end(); ?>
<script>
</script>
</div>
