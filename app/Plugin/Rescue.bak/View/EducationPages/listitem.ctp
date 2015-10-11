<?
#$children = !empty($page['children']) ? $page['children'] : null;
if(!empty($page['EducationPage'])) { $page = $page['EducationPage']; } # Share with sub records.
?>

<div class='padding10' id="EducationPage_<?= $page['id'] ?>">
	<?= $this->Html->link($page['title'], array('plugin'=>'rescue','controller'=>'education_pages','action'=>'view',$page['idurl'])); ?>
	<div class='indent'>
		<?= $this->Html->summary($page['content']); ?>
	</div>
	<? /* if(!empty($children)) { ?>
	<div class='children sortablehide marginleft25' Xstyle="display: none;" id="Children_<?= $page['id'] ?>">
		<? foreach($children as $childpage) { ?>
		<?= $this->element("../Pages/listitem", array('page'=>$childpage)); ?>
		<? } ?>
	</div>
	<? } */ ?>

</div>


