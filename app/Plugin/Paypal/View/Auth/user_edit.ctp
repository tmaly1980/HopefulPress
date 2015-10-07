<? $this->assign("page_title", "Setup Your Paypal Account");  ?>
<? $this->start("title_controls"); ?>
<? $this->end(); ?>
<div class='form'>
	<? if(empty($this->request->data['PaypalCredential']['id'])) { ?>
	<div class='alert alert-info'>
		If you do not yet have a Paypal account, 
		<?= $this->Html->link("Sign up for a Paypal account", "http://www.paypal.com/",array('target'=>'_new')); ?> before continuing.
	</div>
	<? } ?>

	<?= $this->Form->create("PaypalCredential");#,array('url'=>$this->here)); ?>
	<?= $this->Form->hidden('id'); ?>

	<?= $this->Form->input("account_email", array('label'=>'Your Paypal account email','note'=>'Please double check for any typos or other errors')); ?>


	<?= $this->Form->save("Save Account",array('cancel'=>"/donation")); ?>
</div>
