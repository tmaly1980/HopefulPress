<?= $this->element("Core.js/editor"); ?>
<? $id = !empty($this->request->data['Ticket']['id']) ? $this->request->data['Ticket']['id'] : null;  ?>
<? $this->assign("page_title", (!empty($id)?"Edit":"Add")." Support Ticket"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All tickets",array('action'=>'index')); ?>
<? $this->end(); ?>

<div class='view'>
<?=$this->Form->create("Ticket",array('url'=>array('action'=>'edit',$id))); ?>
<?= $this->Form->hidden("id"); ?>
<?= $this->Form->title("title",array('placeholder'=>'Briefly summarize your problem...','label'=>false)); ?>

<?= $this->Form->input("description",array('label'=>'Details','placeholder'=>'Add details here...','class'=>'editor mediaonly')); ?>
<div class='tip'>
Please include as much detail as possible describing your problem as well as any links and screenshots if possible.
</div>

<hr/>

<?= $this->Form->save($id?"Update ticket":"Submit ticket"); ?>
<?= $this->Form->end(); ?>

</div>
