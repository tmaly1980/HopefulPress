<div id="LinkCategories" class="LinkCategories">
	<? foreach($linkCategories as $cat) { ?>
	<div class='item' id="LinkCategory_<?= $cat['LinkCategory']['id'] ?>">
		<h3 id="LinkCategory_Name_<?= $cat['LinkCategory']['id'] ?>" class="">
			<?= $cat['LinkCategory']['name'] ?>
		</h3>
		<div class="clear"></div>
		<? if(true) { //$in_admin && $this->Admin->access()) { ?>
		<script>
		$('#LinkCategory_Name_<?= $cat['LinkCategory']['id'] ?>').inline_edit({tooltip: 'Click to edit category name'});
		</script>
		<? } ?>
		<div id="Links_<?= $cat['LinkCategory']['id'] ?>" class="Links">
			<?= $this->element("../Links/list", array('links'=>$cat['Link'],'category'=>$cat)); ?>
		</div>
	</div>
	<? } ?>
</div>

