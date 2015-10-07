<?
$requestTypes = array(
	'question'=>"I have a general question",
	'problem'=>"I am experiencing a problem with a Hopeful Press website",
	'feature'=>"I would like to request a new website feature",
);
?>
<? $this->assign("page_title", "Contact Us"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>

<div class='row'>
<div class="col-md-6 ">
	<h3>Email Us</h3>

	<?php echo $this->Form->create('ContactRequest', array('role' => 'form','type'=>'file')); ?>
	<div class='row'>
		<? if($this->Html->me()) { ?>
			<?= $this->Form->hidden("user_id",array('value'=>$current_user['User']['id'])); ?>
			<?= $this->Form->hidden("site_id",array('value'=>$current_user['User']['site_id'])); ?>
		<? } else { ?>
			<?= $this->Form->input("email",array('div'=>'col-md-6','label'=>'Your Email','required'=>1)); ?>
			<?= $this->Form->input("name",array('div'=>'col-md-6','label'=>'Your Name','required'=>1)); ?>
			<?= $this->Form->input("organization",array('div'=>'col-md-6','label'=>'Your Organization (optional)')); ?>
			<?= $this->Form->input("website",array('div'=>'col-md-6','label'=>'Your Current Website (optional)')); ?>
		<? } ?>
	</div>
		<?= $this->Form->input("request_type", array('options'=>$requestTypes,'label'=>'Purpose')); ?>
		<?= $this->Form->input("message",array('required'=>1,'label'=>'Your Message/Details','rows'=>6)); ?>

		<?= $this->Form->save("Send",array('cancel'=>false)); ?>
	<div  class='alert alert-info'>
		We will get back to you as soon as possible.
	</div>
	
	<?php echo $this->Form->end() ?>
	
</div>
<div class='col-md-6'>
	<h3>Social Media</h3>
	<div  class='alert alert-info'>
		We are also available online through Facebook and Twitter.
	</div>
	<?= $this->Html->link($this->Html->fa("facebook fa-lg"). " &nbsp; Hopeful Press Facebook Page", "http://www.facebook.com/HopefulPress", array('title'=>'Find us on Facebook','class'=>'width250 btn white btn-primary medium')); ?>
	<br/>
	<br/>
	<?= $this->Html->link($this->Html->fa("facebook fa-lg"). " &nbsp; Tomas Maly on Facebook", "http://www.facebook.com/tomashen", array('title'=>'Tomas Maly on Facebook','class'=>'width250 btn white btn-primary medium')); ?>
	<br/>
	<br/>
	<?= $this->Html->link($this->Html->fa("twitter fa-lg")." &nbsp; Hopeful Press on Twitter", "http://www.twitter.com/hopefulpress", array('title'=>'Find us on Twitter','class'=>'width250 btn white btn-info medium')); ?>
</div>
</div>
