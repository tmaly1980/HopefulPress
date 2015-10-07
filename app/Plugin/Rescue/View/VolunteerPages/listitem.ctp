<?
$children = !empty($page['children']) ? $page['children'] : null;
if(!empty($page['VolunteerPage'])) { $page = $page['VolunteerPage']; } # Share with sub records.
?>

<div class='padding10' id="VolunteerPage_<?= $page['id'] ?>">
	<?= $this->Html->link($page['title'], array('controller'=>'volunteer_pages','action'=>'view',$page['idurl'])); ?>
	<div>
		<?= $this->Html->summary($page['content']); ?>
	</div>
	<? if(!empty($children)) { ?>
	<div class='children sortablehide marginleft25' Xstyle="display: none;" id="Children_<?= $page['id'] ?>">
		<? foreach($children as $childpage) { ?>
		<?= $this->element("Rescue.../VolunteerPages/listitem", array('page'=>$childpage)); ?>
		<? } ?>
	</div>
	<? } ?>
</div>


