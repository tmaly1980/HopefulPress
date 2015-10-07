<?
$children = !empty($page['children']) ? $page['children'] : null;
if(!empty($page['Page'])) { $page = $page['Page']; } # Share with sub records.
?>

<div class='padding10' id="Page_<?= $page['id'] ?>">
	<? if(false && !empty($children)) { # NONE FOR NOW, DISABLED ?>
		<?= $this->Html->link("<span class='glyphicon glyphicon-triangle-right'></span>", "javascript:void(0)", array('id'=>"Page_toggler_{$page['id']}")); ?>
		<script>
		j('#Page_toggler_<?= $page['id'] ?>').toggle(function() {
			var toggler = this;
			var children = $('#Children_<?= $Page['id'] ?>');
			if($(toggler).hasClass('glyphicon-triangle-right'))
			{
				$(toggler).removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
				$(children).show();
			} else {
				$(toggler).addClass('glyphicon-triangle-right').removeClass('glyphicon-triangle-bottom');
				$(children).hide();
			}

		});
		</script>
	<? } ?>
	<?= $this->Html->link($page['title'], array('action'=>'view',$page['idurl'])); ?>
	<div>
		<?= $this->Html->summary($page['content']); ?>
	</div>
	<? if(!empty($children)) { ?>
	<div class='children sortablehide marginleft25' Xstyle="display: none;" id="Children_<?= $page['id'] ?>">
		<? foreach($children as $childpage) { ?>
		<?= $this->element("../Pages/listitem", array('page'=>$childpage)); ?>
		<? } ?>
	</div>
	<? } ?>

</div>


