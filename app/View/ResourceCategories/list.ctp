<div id="ResourceCategories" class="ResourceCategories">
	<? foreach($resourceCategories as $cat) { ?>
	<div class='item' id="ResourceCategory_<?= $cat['ResourceCategory']['id'] ?>">
		<h3 id="ResourceCategory_Name_<?= $cat['ResourceCategory']['id'] ?>" class="">
			<?= $cat['ResourceCategory']['name'] ?>
		</h3>
		<div class="clear"></div>
		<? if(true) { //$in_admin && $this->Admin->access()) { ?>
		<script>
		$('#ResourceCategory_Name_<?= $cat['ResourceCategory']['id'] ?>').inline_edit({tooltip: 'Click to edit category name'});
		</script>
		<? } ?>
		<div id="Resources_<?= $cat['ResourceCategory']['id'] ?>" class="Resources">
			<?= $this->element("../Resources/list", array('resources'=>$cat['Resource'],'category'=>$cat)); ?>
		</div>
	</div>
	<? } ?>
</div>

