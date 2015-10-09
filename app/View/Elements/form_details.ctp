<? if(is_array($v)) { ?>
<tr>
	<th><?= Inflector::humanize($k) ?>:</th>
	<td>
		<table>
		<? foreach($v as $sk=>$sv) { ?>
		<tr>
			<th class="top_align"><?= Inflector::humanize($sk) ?>: </th>
			<td><? if(is_array($sv)) { ?>
				<table>
				<? foreach($sv as $skk=>$svv) { ?>
				<tr>
					<th><?= Inflector::humanize($skk) ?>: </th>
					<td><?= is_numeric($svv) ? ($v === 0 ? "No" : "Yes") : $this->Text->autolink($svv) ?></td>
				</tr>
				<? } ?>
				</table>
			<? } else { ?>
				<?= is_numeric($sv) ? ($v === 0 ? "No" : "Yes") : $this->Text->autolink($sv) ?>
			<? } ?>
			</td>
		</tr>
		<? } ?>
		</table>
	</td>
</tr>
<? } else if(!empty($v)) { ?>
<tr>
	<th><?= Inflector::humanize($k) ?>: </th>
	<td><?= is_array($v) ? join(", ", $v) : 
		is_numeric($v) ? ($v === 0 ? "No" : "Yes") : $this->Text->autolink($v) ?>
	</td>
</tr>
<? } ?>

