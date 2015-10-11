<?
$children = !empty($page['children']) ? $page['children'] : null;
if(!empty($page['FosterPage'])) { $page = $page['FosterPage']; } # Share with sub records.
?>

<div class='padding10' id="FosterPage_<?= $page['id'] ?>">
	<?= $this->Html->link($page['title'], array('controller'=>'foster_pages','action'=>'view',$page['idurl'])); ?>
	<div>
		<?= $this->Html->summary($page['content']); ?>
	</div>
	<? if(!empty($children)) { ?>
	<div class='children sortablehide marginleft25' Xstyle="display: none;" id="Children_<?= $page['id'] ?>">
		<? foreach($children as $childpage) { ?>
		<?= $this->element("Rescue.../FosterPages/listitem", array('page'=>$childpage)); ?>
		<? } ?>
	</div>
	<? } ?>

</div>


