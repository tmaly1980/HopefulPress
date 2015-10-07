<?= $this->element("Sortable.js"); ?>

<? $pid = Configure::read("project_id"); ?>
<? $this->assign("page_title", !empty($linkPage['LinkPage']['title']) ? $linkPage['LinkPage']['title'] : 'Links'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add Link", array("user"=>1,"controller"=>'links',"action"=>"add")); ?>
	<? if(count($links) > 1) { # XXX somehow count for links within categories ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'link_sorter')); ?>
	<? } ?>
<? } ?>
<? $this->end(); ?>



<div class="pages form">

	<div id="LinkPage_Introduction">
	<? if(!empty($linkPage['LinkPage']['introduction'])) { ?>
		<?= nl2br($linkPage['LinkPage']['introduction']); ?>
	<? } ?>
	</div>

	<? if($this->Html->can_edit()) { ?>
	<script>
	$('#LayoutTitle').inline_edit({prefix: "admin", link: '', inline:true, model: 'LinkPage',field:'title',class: 'margintop10',append: "<?= !empty($pid) ? "project_id:$pid" : "" ?>"});
	$('#LinkPage_Introduction').inline_edit({prefix: "admin", link: 'Add introduction/Edit introduction',append: "<?= !empty($pid) ? "project_id:$pid" : "" ?>"});
	</script>
	<? } ?>
	<div class='clear'></div>

	<div id="Links" class='margintop25'>
		<?= $this->element("../Links/list"); ?>

		<? if(!empty($linkCategories)) { ?>
		<div class='categorylist'>
			<? foreach($linkCategories as $cat) { ?>
			<div id="LinkCategory_<?= $cat['LinkCategory']['id'] ?>" class='LinkCategory'>
				<h3 id="LinkCategory_Title_<?= $cat['LinkCategory']['id'] ?>"><?= $cat['LinkCategory']['title'] ?></h3>
				<? if($this->Html->can_edit($cat['LinkCategory'])) { ?>
				<script>
					$('#LinkCategory_Title_<?= $cat['LinkCategory']['id'] ?>').inline_edit({prefix: "user", link: 'Add category title/Rename category',inline:true});
				</script>
				<? } ?>
				<div class='clear'></div>
				<?= $this->element("../Links/list",array('category'=>$cat,'links'=>$cat['Link'])); ?>
			</div>
			<? } ?>
		</div>
		<? } ?>
	</div>

	<? if($this->Html->can_edit()) { ?>
	<script>
	<? if(count($linkCategories) > 1) { // XXX will this work? Can we move BETWEEN categories? ?>
	$('#link_sorter').sortInit('.categorylist',{prefix: "user", axis: 'y',controller: 'linkCategories'});
	<? } ?>
	<? if($linkCount > 1) { # total, in cats and out ?>
	$('#link_sorter').sortInit('.linklist',{prefix: "user", axis: 'y',controller: 'links'}).sortEnable();
	<? } ?>
	</script>
	<? } ?>

</div>
