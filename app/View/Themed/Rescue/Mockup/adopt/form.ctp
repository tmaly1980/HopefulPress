<script>$.dialogtitle('Adoption Form - Mr. Wiggles');</script>
<div class='view'>

<?= $this->Form->create("AdoptionRequest",array('url'=>"/mockup/goto/adopt_thanks",'class'=>'condensed')); ?>
		<p>Interested in adopting  Mr. Wiggles? Contact us via the form below:

		<div class='row'>
			<?= $this->Form->input("full_name",array('div'=>'col-md-6','label'=>'Your Full Name(s)')); ?>
			<?= $this->Form->input("email",array('div'=>'col-md-6','label'=>'Your Email')); ?>
		</div>
		<div class='row'>
			<?= $this->Form->input("phone",array('label'=>'Phone Number','div'=>'col-md-6')); ?>
			<?= $this->Form->input("phone_hours",array('label'=>'Best time to contact','div'=>'col-md-6')); ?>
		</div>
			<?#= $this->Form->input("address",array()); ?>
		<div class='row'>
			<?= $this->Form->input("city",array('div'=>'col-md-6')); ?>
			<?= $this->Form->input("state",array('div'=>'col-md-6')); ?>
		</div>
		<?= $this->Form->input("about_yourself",array('type'=>'textarea','label'=>'Tell us a bit about yourself')); ?>

		<?= $this->Form->save("Send adoption request"); ?>
		<?= $this->Form->end(); ?>
</div>

