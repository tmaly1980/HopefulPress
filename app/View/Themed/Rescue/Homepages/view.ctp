<?= $this->element("defs/homepage"); ?>
<? $this->assign("page_title", $homepage['Homepage']['title'] ); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Edit Homepage", array("admin"=>1,"action"=>"edit")); ?>
<? } ?>
<? $this->end(); ?>
<? $this->start("pre_content"); ?>
	<?= $this->element("PagePhotos.view",array('width'=>'625','align'=>'block','margin'=>false,'class'=>'paddingbottom25')); ?>
<? $this->end(); ?>

<?= $this->Facebook->init(); ?>
<? $this->start("rightcol"); ?>
        <? if(!empty($homepage['Homepage']['facebook_like_url'])) { ?>
        <?= $this->Facebook->likebox($homepage['Homepage']['facebook_like_url'], array('width'=>280)); ?>
        <? } else { ?>
                <?= $this->element("adoptables",array('type'=>'carousel')); ?>
        <? } ?>
<? $this->end(); ?>



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

