<? $this->assign("container_class", "maxwidth900"); ?>
<? $id = !empty($this->data['User']['id']) ? $this->data['User']['id'] : null; ?>
<? $this->assign("page_title", "Update Site/User Settings"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink('back',"All Users", array('action'=>'index')); ?>
<? $this->end(); ?>

<div class='users form border lightgreybg '>
<?= $this->Form->create("Site",array('autocomplete'=>'off','id'=>"SiteForm")); ?>

	<?= $this->Form->input('user_id',array('label'=>'Site Owner','empty'=>'- None -')); ?>
	<div class='alert alert-info'>
		The Site owner can:
			<ul>
				<li>Create other users</li>
				<li>Customize the website design</li>
				<li>Update sensitive information such as the home page, about page and contact information</li>
				<li>Change billing information</li>
				<li>Cancel the website at any time</li>
			</ul>
	</div>
	<?= $this->Form->save("Update Settings"); ?>
	<?= $this->Form->end(); ?>
	<div class='clear'></div>
	<script>
	</script>
</div>
