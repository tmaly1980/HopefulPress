<? # OLD..... maybe convert to foster profile signup form???

$id = !empty($this->request->data['Foster']['id']) ? $this->request->data['Foster']['id'] : null;
<? if(false) {#!$this->Rescue->dedicated()) { # Will have to figure out how to handle dealing with dedicated sites

# ???? how would a foster be able to sign-in if not permitted yet on this dedicated site?
# Deny them access to /user/* - but give them a useful error message: 'sorry, only fosters for RESCUE can do that. If youd like to apply as a foster, send a request'
# 
?>
<div class='bold right_align'>
	Already have a Hopeful Press foster account? 
	<?= $this->Html->user("Sign in",array('controller'=>'users','action'=>'login')); ?> to use your existing foster profile.
</div>
<? } ?>
<!-- make it long, then give them the control to remove sections if they think it's too long and don't need certain parts -->
<!-- just go with what I have and ask if anything missing, that should add... -->
<? $title = !empty($fosterForm['FosterForm']['title']) ? $fosterForm['FosterForm']['title'] : "Foster Application"; ?>
<? if($this->request->params['controller'] == 'fosters') { ?>
	<? $this->assign("page_title", $title); ?>
	<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Foster Information", array('admin'=>false,'action'=>'index')); ?>
	<? if($this->Html->can_edit()) { ?>
		<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'foster_forms','action'=>'edit')); ?>
	<? } ?>
	<? $this->end("title_controls"); ?>
<? } else { ?>
	<!--<h3><?= $title  ?></h3>-->
	<? if($this->Html->can_edit()) { ?>
	<div class='right'>
		<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'foster_forms','action'=>'edit'),array('short'=>false)); ?>
	</div>
	<? } ?>
<? } ?>

<div class='form '>
	<p id='FosterForm_introduction'>
		<?= nl2br($fosterForm['FosterForm']['introduction']) ?>
	</p>

	<?= $this->Form->create("Foster",array('url'=>array('controller'=>'fosters','action'=>'add'))); ?>
		<?= $this->Form->hidden("id"); ?>

		<?= $this->element("forms/admin_status"); ?>
		<?= $this->element("forms/about"); ?>
		<?= $this->element("forms/home"); ?>
		<?= $this->element("forms/foster"); ?>
		<hr/>
	<? if(!empty($fosterForm['FosterForm']['acknowledgment'])){ ?>
	<h3>Acknowledgment/Disclaimer</h3>
	<? } ?>

	<div id='FosterForm_acknowledgment'>
		<?= $fosterForm['FosterForm']['acknowledgment'] ?>
	</div>

	<hr/>

		<?= $this->Form->save(!empty($id)?"Update Application":"Submit Application",array('cancel'=>false)); ?>

		<?= $this->Form->end(); ?>
	</div>
</div>
