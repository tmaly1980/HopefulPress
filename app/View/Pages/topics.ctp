<? # Only show 4 columns if at least 4. Try to stretch topics that're there. But at most 1/3rd of row.
$cols = count($topics) >= 4 ? 3 : 4;
?>
<? if(!empty($topics)) { ?>
<div id='topics' class='row margintop25'>
	<? foreach($topics as $topic) { 

		$intro = $this->Html->summary($topic['Page']['content']);
	?>
	<div class='col-md-<?= $cols ?>'>
	<div class='widget'>
	<div>
		<? if(!empty($topic['Page']['page_photo_id'])) { ?>
			<?= $this->element("PagePhotos.thumb", array('id'=>$topic['Page']['page_photo_id'],'href'=>"/page/".$topic['Page']['idurl'])); ?>
		<? } ?>
		<?= $this->Html->titlelink($topic['Page']['title'], "/page/".$topic['Page']['idurl']); ?>
		<? if(!empty($intro)) { ?>
			<div><?= $intro ?></div>
			<div align='right'>
				<?= $this->Html->link("read more...", "/page/".$topic['Page']['idurl'], array('class'=>'btn')); ?>
			</div>
		<? } ?>
	</div>
	</div>
	</div>
	<? } ?>
</div>
<? } else if($this->Html->is_admin()) { ?>
<div id='topics' class='row margintop25 alert-warning dashed border2 padding25'>
	<?= $this->Html->add("Add some topic pages", array('controller'=>'pages','action'=>'add'),array('class'=>'controls btn-warning')); ?> to list here and educate new visitors
</div>
<? } ?>
<div class='clear'></div>
