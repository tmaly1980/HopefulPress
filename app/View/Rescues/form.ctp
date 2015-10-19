<? $manager = !empty($this->request->params['manager']); ?>

<?php echo $this->Form->create('Rescue', array('role' => 'form')); ?>
		<?= $this->Form->hidden('id'); ?>
		<?= $this->Form->input('title', array('placeholder' => 'Your Rescue Name','label'=>false,'required'=>1,'tip'=>'This will appear at the top of each page on your website'));?>

		<?= $this->Form->input_group('hostname', array('validate'=>1,'class'=>'right_align','label'=>"Your website's address (URL)", 'placeholder' => 'hostname','after'=>'.hopefulpress.com','tip'=>'Letters and numbers only (no spaces or punctuation)','required'=>1)); ?>
		<script>
			$('#RescueHostname').filter_input({regex: '[^a-zA-Z0-9_]+', replace: '-'});
		</script>
		<div class='alert alert-info'>
			You'll be able to use your own domain address (ie <b>YourRescue.org</b>) once you upgrade to a paid account.
			If you do not have your own domain yet, let us know and we can purchase it for you.
		</div>

		<h3>Your Information</h3>
		<input type='text' style='display:none;'/>
		<input type='password' style='display:none;'/>

		<?= $this->Form->input('Owner.email', array('placeholder' => 'Your Email','label'=>false,'required'=>($manager?0:1)));?>
		<div class='row'>
			<?= $this->Form->input('Owner.first_name', array('placeholder' => 'First Name','label'=>false,'div'=>'col-md-6','required'=>($manager?0:1)));?>
			<?= $this->Form->input('Owner.last_name', array('placeholder' => 'Last Name','label'=>false,'div'=>'col-md-6','required'=>($manager?0:1)));?>
		</div>
		<?= $this->Form->input('Owner.password', array('placeholder' => 'Password','label'=>false,'required'=>($manager?0:1))); ?>
		<?#= $this->Form->input('Owner.password2', array('placeholder' => null,'type'=>'password','label'=>'Verify password')); ?>

		<?= $this->Form->hidden("trial",array('value'=>1)); # We need to recognize this and also clear this once payment is provided... ?>

		<? if(!empty($plans) && !empty($plan))  { ?>
		<?= $this->Form->hidden("plan",array('value'=>$plan)); ?>
		<div class='bold'>Selected Plan: <?= $plans[$plan]['metadata']['title']; ?> ($<?= $plans[$plan]['amount']/100 ?>/<?= $plans[$plan]['interval']?>)</div>
		<? } ?>
		<div class='alert alert-info'>
		We won't ask for a credit card just yet. After your 30-day free trial, you'll be able to provide your billing information to continue. 
		</div>
	<?= $this->Form->save("Preview Site",array('cancel'=>false)); ?>
<?php echo $this->Form->end() ?>
