<?
class HpCommentsHelper extends AppHelper
{
	function comments($options = array())
	{
		if(empty($options['modelClass']))
		{
			$options['modelClass'] = $this->Form->model();
		}
		if(empty($options['id']))
		{
			$options['id'] = $this->Form->id();
		}

		ob_start();

		?>
		<div id="HpCommentsContainer" class="hpComments index bordertop paddingtop5">
		</div>
		<script>
			// this gets called as content gets eval'ed, but before gets placed into modaltmp OR modal
			$('#HpCommentsContainer').load("/hp_comments/hp_comments/comments/<?=$options['modelClass']?>/<?=$options['id']?>", function() {
				//console.log("HCC CALLBACK");
				<? if(!empty($options['callback'])) { ?>
					<?= $options['callback']; ?>
				<? } ?>
			});
		</script>
		<?
		return ob_get_clean();
	}
}
