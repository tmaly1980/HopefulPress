<? $this->assign("page_title", "Edit Homepage"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View Homepage", array("action"=>"view")); ?>
<? $this->end(); ?>

<div class="pages form">
	<?php echo $this->Form->create('Homepage', array('role' => 'form')); ?>

		<?php echo $this->Form->title('title', array('label'=>false, 'placeholder' => 'Homepage Title'));?>

		<div class='row'>
			<div class='col-md-6'>
				<?= $this->element("PagePhotos.edit",array('class'=>'maxwidth100p','width'=>625)); ?>

				<?= $this->Form->input("introduction", array('label'=>'Introduction','class'=>'autogrow double')); ?>
			</div>
			<div class='col-md-6'>
				<?= $this->Form->input("facebook_like_url",array('label'=>"Add Facebook URL for 'Like' box (optional)")); ?>
				<div class='alert alert-info'>A Facebook "Like" box allows  fans to like your page without leaving your website. You can still add a Facebook icon in your header through the "Site Settings &gt; Theme/Design" page.</div>
				<div class='alert alert-warning'>
					NOTE: Current adoptables will be displayed in this corner by default when without a Facebook page box. Otherwise, adoptables will show automatically below.
				</div>
			</div>
		</div>

		<?= $this->element("../Pages/topics",  array('editable'=>true,array('rescueHomepage'=>$this->request->data))); ?>

		<div class='row'>
			<div class='col-md-6'>
				&nbsp;
			</div>
			<div class='col-md-6'>
				<?= $this->Form->title("sidebar_title", array('label'=>false,'placeholder'=>'Sidebar Title')); ?>
				<?= $this->Form->input("sidebar_content", array('rows'=>10,'class'=>'editor','tip'=>"Optional HTML content including affiliate ads, newsletter subscription form, etc")); ?>
				<?= $this->Form->save("Update Homepage",array('cancel'=>array('action'=>'view'))); ?>
			</div>
		</div>

	<?php echo $this->Form->end() ?>

	<div class='clear'></div>
	<script>
	</script>
</div>

<div style='background-color: green; color: purple;'>
	hello dear.
</div>
