<? $id = !empty($this->request->data['MailAlias']['id']) ? $this->request->data['MailAlias']['id'] : null; ?>
<? if($id) { ?>
<? $this->start("title_controls");?>
	<?= $this->Html->delete("Delete email forwarder",array('action'=>'delete',$id),array('confirm'=>"Are you sure you want to remove this email forwarder? All future emails sent to this address will be rejected.")); ?>
<? $this->end("title_controls");?>
<? } ?>
<?= $this->assign("page_title", !empty($id)?"Edit Email Forwarder":"Add Email Forwarder"); ?>
<div class='form'>
<?= $this->Form->create("MailAlias"); ?>
	<?= $this->Form->hidden('id'); ?>
	<?= $this->Form->input_group("alias",array('label'=>"Virtual email address",'required'=>1,'class'=>'right_align','after'=>"@".$this->Site->get("domain"))); ?>
	<?= $this->Form->input("recipients",array('label'=>'Email recipients, one per line','required'=>1)); ?>
	<?= $this->Form->save(); ?>
<?= $this->Form->end(); ?>
</div>
