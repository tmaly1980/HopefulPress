<h3>Comments</h3>
<?= $this->element("HpComments.comments_pager"); ?>

<div id="HpComments">
<? if(!empty($comments)) { ?>
	<? foreach($comments as $c) { ?>
		<?= $this->element("HpComments.../HpComments/view", array('comment'=>$c)); ?>
	<? } ?>
<? } else { ?>
	<!--
	<div class="nodata">There are no previous comments</div>
	-->
<? } ?>
</div>

<div id="HpCommentsAdd">
<?= $this->element("HpComments.../HpComments/add"); ?>
</div>
