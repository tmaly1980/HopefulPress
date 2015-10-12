<?= $this->element("Sortable.js"); ?>

<? $pid = Configure::read("project_id"); ?>
<? $this->assign("page_title", !empty($resourcePage['ResourcePage']['title']) ? $resourcePage['ResourcePage']['title'] : 'Resources'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add Resource", array("admin"=>1,"controller"=>'resources',"action"=>"add")); ?>
	<? if(count($resources) > 1) { # XXX somehow count for resources within categories ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'resource_sorter')); ?>
	<? } ?>
<? } ?>
<? $this->end(); ?>



<div class="pages form">
	<? if($this->Html->can_edit()) { ?>
	<div  class='alert alert-info'>
		<b>Resources</b> allow you to share names, addresses, phone numbers and websites of useful information and organizations.
	</div>
	<? } ?>

	<div id="ResourcePage_Introduction">
	<? if(!empty($resourcePage['ResourcePage']['introduction'])) { ?>
		<?= nl2br($resourcePage['ResourcePage']['introduction']); ?>
	<? } ?>
	</div>

	<? if($this->Html->can_edit()) { ?>
	<script>
	$('#LayoutTitle').inline_edit({prefix: "user", link: '', inline:true, model: 'ResourcePage',field:'title',class: 'margintop10',append: "<?= !empty($pid) ? "project_id:$pid" : "" ?>"});
	$('#ResourcePage_Introduction').inline_edit({prefix: "user", link: 'Add introduction/Edit introduction',append: "<?= !empty($pid) ? "project_id:$pid" : "" ?>"});
	</script>
	<? } ?>
	<div class='clear'></div>

	<div id="Resources" class='margintop25'>
	<? if(empty($resources) && empty($resourceCategories)) { ?>
		<div class='nodata'>There are no resources available yet</div>
	<? } else { ?>
		<? if(!empty($resources) || empty($resourceCategories)) { ?>
		<?= $this->element("../Resources/list"); ?>
		<? } ?>

		<? if(!empty($resourceCategories)) { ?>
		<div class='categorylist'>
			<? foreach($resourceCategories as $cat) { ?>
			<div id="ResourceCategory_<?= $cat['ResourceCategory']['id'] ?>" class='ResourceCategory'>
				<h3 class="bold marginbottom0" id="ResourceCategory_Title_<?= $cat['ResourceCategory']['id'] ?>"><?= $cat['ResourceCategory']['title'] ?></h3>
				<? if($this->Html->can_edit($cat['ResourceCategory'])) { ?>
				<script>
					$('#ResourceCategory_Title_<?= $cat['ResourceCategory']['id'] ?>').inline_edit({prefix: "user", resource: 'Add category title/Rename category',inline:true});
				</script>
				<? } ?>
				<div class='clear'></div>
				<div class="paddingleft25">
					<?= $this->element("../Resources/list",array('category'=>$cat,'resources'=>$cat['Resource'])); ?>
				</div>
			</div>
			<? } ?>
		</div>
		<? } ?>
	<? } ?>
	</div>

	<? if($this->Html->can_edit()) { ?>
	<script>
	<? if(count($resourceCategories) > 1) { // XXX will this work? Can we move BETWEEN categories? ?>
	$('#resource_sorter').sortInit('.categorylist',{prefix: "user", axis: 'y',controller: 'resourceCategories'});
	<? } ?>
	<? if($resourceCount > 1) { # total, in cats and out ?>
	$('#resource_sorter').sortInit('.resourcelist',{prefix: "user", axis: 'y',controller: 'resources'}).sortEnable();
	<? } ?>
	</script>
	<? } ?>

</div>
