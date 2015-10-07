<?= $this->Form->create("Donation", array('id'=>"DonateForm", 'url'=>"/donation/donate")); ?>
	<? if(!empty($adoptable_id)) { ?>
		<?= $this->Form->hidden("adoptable_id",array('value'=>$adoptable_id)); ?>
	<? } ?>
<div class='row'>
<div class='col-md-6'>
	<?= $this->Form->hidden('amount', array('class'=>'DonateAmount','default'=>$defaultAmount)); ?>

	<?= $this->Form->input("amount_option", array('type'=>'radio','options'=>$amounts,'default'=>$defaultAmount,'class'=>'DonateAmounts','div'=>'inline-radio')); ?>
	<?= $this->Form->input("other", array('label'=>'Other Amount','class'=>'DonateOther','size'=>7,'div'=>'')); ?>
	<div class='clear'></div>
	<!-- XXX TODO require 'other' being set if no amount set -->
	<?= $this->Form->input("recurring", array('label'=>'This is a recurring (monthly) contribution','type'=>'checkbox')); ?>
	<div class='row'>
	<?= $this->Form->input("name", array('div'=>'col-md-6','label'=>'Your Name'));  ?>
	<?= $this->Form->input("email", array('div'=>'col-md-6','label'=>'Your Email','required'=>1)); ?>
	</div>

	<?= $this->Form->input("note", array('label'=>'Note (optional)','type'=>'text')); ?>

	<!-- XXX TODO let them specify pledge amounts, and descriptions ($100 for shots, $250 food for 6 months) -->

	<?#= $this->Form->input("subscribe", array('type'=>'checkbox','label'=>"I'd like to join your mailing list and receive on-going email notices and newsletters")); ?>
</div>
<div class='col-md-6'>
	<!-- XXX FIX CARD INFO and SUBMISSION -->
	<?= $this->Form->input('card_number', array('name'=>false,'label'=>'Credit Card Number','type'=>'text', 'size'=>20, 'data-stripe'=>'number')); ?>
	<?= $this->Form->input('card_name', array('name'=>false,'label'=>'Name on Card','type'=>'text', 'size'=>20, 'data-stripe'=>'name')); ?>
	<?= $this->Form->expiration('exp_month', 'exp_year', array('data-stripe'=>"exp-month",'name'=>false),array('data-stripe'=>"exp-year",'name'=>false));  ?>

	<!-- SSL CERT ICON -->

	<br/>
	<br/>
	<br/>
	<?= $this->Form->save("Donate Now",array('cancel'=>false)); ?>
</div>
</div>
<?= $this->Form->end(); ?>

<?= $this->Stripe->init("DonateForm");
