<? $id = !empty($this->request->data['Question']['id']) ? $this->request->data['Question']['id'] : null;  ?>
<? $this->assign("page_title", (!empty($id)?"Edit":"Add")." Support Question"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("All questions",array('action'=>'index')); ?>
<? $this->end(); ?>

<div class='view'>
<?=$this->Form->create("Question",array('url'=>array('action'=>'edit',$id))); ?>
<?= $this->Form->hidden("id"); ?>
<?= $this->Form->title("title",array('placeholder'=>'Briefly summarize your question...','label'=>false)); ?>

<?= $this->Form->input("description",array('label'=>'Details','placeholder'=>'Add details here...')); ?>

<? if($this->Html->user("manager")) { ?>
<div id='QuestionCategory'>
	 <?= $this->requestAction("/support/question_categories/select/$contact_id", array('return')); ?>
</div>

<?= $this->Html->input("answer",array('class'=>'editor')); ?>
<? } ?>


<hr/>

<?= $this->Form->save($id?"Update question":"Submit question"); ?>
<?= $this->Form->end(); ?>

</div>
