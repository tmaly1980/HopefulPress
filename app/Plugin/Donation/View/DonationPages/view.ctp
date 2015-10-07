<?= $this->assign("page_title", $donationPage['DonationPage']['title']); ?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View Donations", array('admin'=>1,'controller'=>'donations','action'=>'index')); ?>
	<?= $this->Html->edit("Edit Page", array('admin'=>1,'action'=>'edit')); ?>
<? $this->end(); ?>
<? } ?>

<div class='view'>
	<?= $donationPage['DonationPage']['introduction'] ?>
</div>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit(empty($donationPage['DonationPage']['introduction']) ? "Add introduction":"Edit introduction", array('admin'=>1,'action'=>'edit')); ?>
<? } ?>

<div>

<?= $this->requestAction("/donation/donate",array('return')); ?>

<? if($this->Html->can_edit() || !empty($donationPage['DonationPage']['wishlist'])) { ?>
<br/>
<br/>
<hr/>

<h3 id='wishlist'>Donation Wish List</h3>
<div>
	<? if(empty($donationPage['DonationPage']['wishlist'])) { ?>
		<?= $this->Html->link("Add wish list", array('action'=>'edit'),array('class'=>'btn btn-primary btn-xs white')); ?>
	<?  } else { ?>
	<div>
		<?= $donationPage['DonationPage']['wishlist']; ?>
	</div>
	<?= $this->Html->link("Edit wish list", array('action'=>'edit'),array('class'=>'btn btn-primary btn-xs white')); ?>
	<? } ?>
</div>
<? } ?>
