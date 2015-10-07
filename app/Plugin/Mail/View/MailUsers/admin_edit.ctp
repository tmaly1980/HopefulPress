<? $id = !empty($this->request->data['MailUser']['id']) ? $this->request->data['MailUser']['id'] : null; ?>
<? if($id) { ?>
<? $this->start("title_controls");?>
	<?= $this->Html->delete("Delete email account",array('action'=>'delete',$id),array('confirm'=>"Are you sure you want to remove this email account? All future emails sent to this address will be rejected.")); ?>
<? $this->end("title_controls");?>
<? } ?>
<?= $this->assign("page_title", !empty($id)?"Edit Email Account":"Add Email Account"); ?>
<div class='form'>
<?= $this->Form->create("MailUser"); ?>
	<?= $this->Form->hidden('id'); ?>
	<?= $this->Form->input_group("username",array('required'=>1,'after'=>"@".$this->Site->get("domain"))); ?>
	<?= $this->Form->input("password",array('type'=>'password','label'=>'Set password','value'=>'','required'=>empty($id),'note'=>(!empty($id)?'Leave blank to keep existing password':''))); ?>
	<?= $this->Form->save(); ?>
<?= $this->Form->end(); ?>
</div>
