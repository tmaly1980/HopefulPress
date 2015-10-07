<? if(!empty($nav['mailingListEnabled'])) { ?>
<div class="widget">
	<h3>Join Our Mailing List</h3>
<div class='padding10'>
<?= $this->Form->create("Subscriber",array('action'=>'widget')); ?>
	<?= $this->Form->input("email",array('label'=>'Email Address','required'=>true)); ?>
	<?= $this->Form->input("fname",array('label'=>'First Name','required'=>false)); ?>
	<?= $this->Form->input("lname",array('label'=>'Last Name','required'=>false)); ?>
	<?= $this->Form->save("Sign Up",array('cancel'=>false)); ?>
	<br/>
	<br/>
	<?= $this->Html->link("Unsubscribe", "/newsletter/subscribers/unsubscribe"); ?>

<?= $this->Form->end(); ?>
</div>
</div>
<? } ?>
