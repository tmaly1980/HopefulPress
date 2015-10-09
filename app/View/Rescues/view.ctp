<? #$this->assign("page_title", $rescue['Rescue']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?#= $this->Html->edit("Update rescue page", array("rescuer"=>1,"action"=>"edit",'rescue'=>$rescuename)); # MOVED TO NEW ADMIN BAR ?>
<? } ?>
<? $this->end(); ?>
<? $this->start("pre_content"); ?>
	<?= $this->element("PagePhotos.view",array('width'=>'625','align'=>'block','margin'=>false,'class'=>'paddingbottom25')); ?>
<? $this->end(); ?>

<?= $this->Facebook->init(); ?>
<? $this->start("rightcol"); ?>
        <? if(!empty($rescue['Rescue']['facebook_url'])) { ?>
        <?= $this->Facebook->likebox($rescue['Rescue']['facebook_url'], array('width'=>280)); ?>
        <? } else { ?>
                <?#= $this->element("adoptables",array('type'=>'carousel')); ?>
        <? } ?>
<? $this->end(); ?>



<? if(!empty($rescue['Rescue']['about'])) { ?>
<div class='row'>
<div id="RescueIntro" class="pages view col-md-12">
	<div class='minheight100'>
			<p class='medium doublespacing'>
				<?= $rescue['Rescue']['about'] ?>
			</p>
	</div>
</div>
</div>
<? } ?>

<? $updates_sidebar = $this->element("../Rescues/updates_sidebar");  ?>
<? $updates = $this->element("../Rescues/updates");  ?>

<? $this->start("after_content"); ?>
	<?= $this->fetch("before_updates"); # ??? ?>
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
