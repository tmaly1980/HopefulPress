<? $this->assign("page_title", "Cancel Website"); ?>

<div class="sites form">
<?= $this->Form->create("Site"); ?>
	
	<div class='alert alert-danger'>
		We're sorry to see you go! As soon as your cancellation is confirmed, you will no longer be charged past this billing cycle. You'll be able to reinstate your website at any time if you change your mind.
	</div>

	<?= $this->Form->save("Confirm Website Cancellation"); ?>
<?= $this->Form->end(); ?>
</div>
