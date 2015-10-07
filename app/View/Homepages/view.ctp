<?= $this->element("defs/homepage"); ?>
<? $this->assign("page_title", $homepage['Homepage']['title'] ); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Edit Homepage", array("admin"=>1,"action"=>"edit")); ?>
<? } ?>
<? $this->end(); ?>

<?= $this->element("PagePhotos.view"); ?>

<div class='row'>
<div id="HomepageIntro" class="pages view col-md-12">
	<div class='minheight100'>
			<p class='medium doublespacing'>
				<?= $homepage['Homepage']['introduction'] ?>
			</p>
	</div>
</div>
</div>

<? $this->start("after_content"); ?>
	<?= $this->element("../Pages/topics"); ?>

	<?= $this->fetch("before_updates"); # ??? ?>

<? $updates_sidebar = $this->element("../Homepages/updates_sidebar");  ?>
<? $updates = $this->element("../Homepages/updates");  ?>
<div class='row'>

<? if(!empty($updates_sidebar)) { ?>
	<div class='col-md-4 pull-right'>
		<?= $updates_sidebar ?>
	</div>
	<div class='col-md-8 push-left'>
<? } else { ?>
	<div class='col-md-12'>
<? } ?>
	<?= $updates ?>
	</div>
</div>


<? $this->end("after_content"); ?>

