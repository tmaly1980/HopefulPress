<? if(!empty($nav['mailingListEnabled'])) { ?>
<div class="widget">
	<h3>Join Our Mailing List</h3>
<div class='padding10'>
<?= $this->Form->create("Subscriber",array('plugin'=>null,'controller'=>'rescues','action'=>'subscribe'),array('id'=>'SubscriberForm')); ?>
	<?= $this->Form->input("email",array('id'=>'SubscriberEmail','label'=>'Email Address','required'=>true)); ?>
	<?= $this->Form->input("fname",array('label'=>'First Name','required'=>false)); ?>
	<?= $this->Form->input("lname",array('label'=>'Last Name','required'=>false)); ?>
	<?= $this->Form->save("Sign Up",array('cancel'=>false)); ?>
	<br/>
	<br/>
	<?= $this->Html->link("Unsubscribe", '#',array('id'=>'Unsubscribe')); ?>
<?= $this->Form->end(); ?>
	<script>
	$('#Unsubscribe').click(function() {
		if(!$('#SubscriberEmail').val())
		{
			BootstrapDialog.alert('Please specify your email to unsubscribe');
		} else {
			$('#SubscriberForm').attr('action', '<?= Router::url(array('plugin'=>null,'controller'=>'rescues','action'=>'unsubscribe','rescue'=>$rescuename)); ?>');
			$('#SubscriberForm').submit();
		}
	});
	</script>
</div>
</div>
<? } ?>
