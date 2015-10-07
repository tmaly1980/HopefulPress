<? $model = "{$type}PageView"; ?>
<table class='table'>
<tr>
	<th>When</th>
	<th>Page</th>
	<th>Referer</th>
</tr>
<? foreach($pageViews as $pageView) { ?>
<tr>
	<td>
		<?= $this->Time->mondyhm($pageView[$model]['created']); ?>
	</td>
	<td>
		<?= $pageView[$model]['url'] ?>
	</td>
	<td>
		<?= $pageView[$model]['referer']  ?>
		(<?= $pageView[$model]['refdomain']  ?> /
			<?= $pageView[$model]['refqs']  ?>)
	</td>
</tr>
<? } ?>
</table>
