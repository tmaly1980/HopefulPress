<? $adding = empty($this->request->data['Discussion']['id']);  ?>
<? $this->assign("page_title", ($adding?"Start ":"Edit ")."a discussion"); ?>
<div class='form'>
<?= $this->Form->create("Discussion"); ?>
	<?= $this->Form->input("id"); ?>
	<?= $this->Form->input("title",array('placeholder'=>"Subject...",'label'=>false)); ?>
	<?= $this->Form->input("description"); ?>
<?= $this->Form->save("Post"); ?>
<?= $this->Form->end(); ?>
</div>
</div>
