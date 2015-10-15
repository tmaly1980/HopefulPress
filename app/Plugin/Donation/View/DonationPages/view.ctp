<?= $this->assign("page_title", "Support {$rescue['Rescue']['title']}");?>
<? if($this->Html->can_edit()) { ?>
<? $this->start("title_controls"); ?>
	<?#= $this->Html->back("View Donations", array('admin'=>1,'controller'=>'donations','action'=>'index')); ?>
	<?= $this->Html->edit("Customize page", array('admin'=>1,'action'=>'edit','rescue'=>$rescuename)); ?>
<? $this->end(); ?>
<? } ?>

<? if(!empty($donationPage['DonationPage']['introduction'])){ ?>
<div class='view border rounded padding25 margintop10 marginbottom10'>
	<?= $donationPage['DonationPage']['introduction'] ?>
</div>
<?} ?>

<div>

<?= $this->requestAction(array('controller'=>'donations','action'=>'donate','rescue'=>$rescuename),array('return')); ?>

<? if($this->Html->can_edit() || !empty($donationPage['DonationPage']['wishlist'])) { ?>
<br/>
<br/>
<hr/>

<h3 id='wishlist'>Donation Wish List</h3>
<div>
	<? if(empty($donationPage['DonationPage']['wishlist']) && $this->Html->can_edit()) { ?>
		<?= $this->Html->link("Add wish list", array('user'=>1,'action'=>'edit','rescue'=>$rescuename),array('class'=>'btn btn-primary btn-xs white')); ?>
	<?  } else { ?>
	<div>
		<?= $donationPage['DonationPage']['wishlist']; ?>
	</div>
	<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->link("Edit wish list", array('user'=>1,'action'=>'edit','rescue'=>$rescuename),array('class'=>'btn btn-primary btn-xs white')); ?>
	<? } ?>
	<? } ?>
</div>
<? } ?>
