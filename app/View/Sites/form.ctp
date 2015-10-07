<? $manager = !empty($this->request->params['manager']); ?>

<?php echo $this->Form->create('Site', array('role' => 'form')); ?>
		<?= $this->Form->hidden('id'); ?>
		<?= $this->Form->input('title', array('placeholder' => 'Your Site Name','label'=>false,'required'=>1,'tip'=>'This will appear at the top of each page on your website'));?>

		<?= $this->Form->input_group('hostname', array('class'=>'right_align','label'=>"Your website's address (URL)", 'placeholder' => 'hostname','after'=>'.hopefulpress.com','tip'=>'Letters and numbers only (no spaces or punctuation)','required'=>1)); ?>
		<script>
			$('#SiteHostname').filter_input({regex: '[^a-zA-Z0-9_]+', replace: '-'});
		</script>
		<div class='alert alert-info'>
			You'll be able to use your own domain address (ie <b>YourRescue.org</b>) once you upgrade to a paid account.
			If you do not have your own domain yet, let us know and we can purchase it for you. You'll be billed an additional $10/year for registration/renewal.
		</div>

		<?= $this->Form->input("rescue_enabled", array('label'=>'Type of organization','options'=>array(1=>'Rescue organization (short-term foster/adoption)',0=>'Sanctuary (long-term care)'),'default'=>1)); ?>
		<div class='alert alert-info'>
			<b>Rescue organizations</b> will have pages and forms relating to adoptions and fostering, whereas <b>Sanctuary organizations</b> will not. Both types of organizations will be able to receive general and sponsor-style donations.
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
		<div class='bold'>Selected Plan: <?= $plans[$plan]['metadata']['title']; ?> ($<?= $plans[$plan]['amount']/100 ?>/mo)</div>
		<? } ?>
		<div class='alert alert-info'>
		We won't ask for a credit card just yet. After your 30-day free trial, you'll be able to provide your billing information to continue. Just pay month-to-month. No contracts or obligations. Cancel anytime.
		</div>
	<?= $this->Form->save("Create Site",array('cancel'=>false)); ?>
<?php echo $this->Form->end() ?>
