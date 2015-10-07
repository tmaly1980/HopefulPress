<script>$.dialogtitle('Sponsorship Form - Mr. Wiggles');</script>
<div class='view'>

<p>Interested in sponsoring Mr. Wiggles? Provide your payment information below:

<?
$amounts = array(
5=>'$5',
10=>'$10',
15=>'$15',
20=>'$20',
50=>'$50',
100=>'$100',
500=>'$500',
);
$default_amount = 10;
?>

	<div>
	<?= $this->Form->create("Sponsorship",array('url'=>"/mockup/goto/sponsor_thanks")); ?>
	<div class='row'>
		<div class='col-md-8'>
		<?= $this->Form->input("amount", array('options'=>$amounts,'type'=>'radio','default'=>$default_amount,'legend'=>false)); ?>
		</div>
		<?= $this->Form->input_group("other_amount",array('before'=>'$','size'=>10,'div'=>'col-md-4')); ?>
	</div>
	<div class='row'>
		<?= $this->Form->input("name",array('label'=>'Your Name','div'=>'col-md-6')); ?>
		<?= $this->Form->input("email",array('label'=>'Your Email','div'=>'col-md-6')); ?>
	</div>
		<?= $this->Form->input("phone",array('label'=>'Your Phone (optional)')); ?>

	<div class='row'>
		<?= $this->Form->input("credit_card",array('label'=>'Credit card number','div'=>'col-md-6')); ?>
		<div class='col-md-6'>
			<?= $this->Form->expiration("exp_month","exp_year"); ?>
		</div>
	</div>

		<?= $this->Form->input("recurring",array('type'=>'checkbox','label'=>'Make this a recurring (monthly) sponsorship')); ?>
		<?= $this->Form->input("mailing_list",array('type'=>'checkbox','label'=>'Add me to your mailing list with regular organization updates')); ?>

		<?= $this->Form->save("Sponsor Now");  ?>
	<?= $this->Form->end(); ?>
	</div>

</div>
