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
	<?= $this->Form->create("Donate"); ?>
		<?= $this->Form->input("amount", array('options'=>$amounts,'type'=>'radio','default'=>$default_amount)); ?>
		<?= $this->Form->input_group("other_amount",array('before'=>'$','size'=>10)); ?>
		<?= $this->Form->input("recurring",array('type'=>'checkbox','label'=>'Make this a recurring (monthly) contribution')); ?>
		<?= $this->Form->save("Donate Now");  ?>
	<?= $this->Form->end(); ?>
	</div>
