<?php
# Fix fields so displayField is first.
$oldFields = $fields;
$fields = array($displayField);
foreach($oldFields as $f) { if($f != $displayField) { $fields[] = $f; } }

printf('<? $id = !empty($%s["%s"]["id"]) ? $%s["%s"]["id"] : ""; ?>'."\n", $singularVar, $modelClass,$singularVar, $modelClass);
printf('<? $this->assign("page_title", "%s"); ?>'."\n", $singularHumanName); 
printf('<? $this->assign("container_class", ""); ?>'."\n");
printf('<? $this->assign("layout_main_class", ""); ?>'."\n");
printf('<? $this->start("title_controls"); ?>'."\n");
printf('<? if($this->Site->can("edit", $%s)) { ?>'."\n", $singularVar);
printf('	<?= $this->Html->edit("Edit %s", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>'."\n", $singularHumanName);
printf('<? } ?>'."\n");
printf('<? $this->end(); ?>'."\n");
?>
<div class="<?php echo $pluralVar; ?> view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<?php
				foreach ($fields as $field) {
					echo "<tr>\n";
					$isKey = false;
					if($field == $primaryKey) { $isKey = true; }
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								$isKey = true;
								echo "\t\t<th><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></th>\n";
								echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
								break;
							}
						}
					}
					if ($isKey !== true) {
						echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
						echo "\t\t<td>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
					}
					echo "</tr>\n";
				}
				?>
				</tbody>
			</table>
</div>

<?php
if (!empty($associations['hasOne'])) :
	foreach ($associations['hasOne'] as $alias => $details): ?>
	<div class="row related">
		<div class="col-md-12">
			<h3><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "'); ?>"; ?></h3>
			<table class="table table-striped">
			<tbody>
		<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
			<tr>
		<?php
				foreach ($details['fields'] as $field) {
					echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
					echo "\t\t<td>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</td>\n";
				}
		?>
			</tr>
		<?php echo "<?php endif; ?>\n"; ?>
			</tbody>
			</table>
		</div><!-- end col md 12 -->
	</div>
	<?php
	endforeach;
endif;
if (empty($associations['hasMany'])) {
	$associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
	$associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
foreach ($relations as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
	?>
<div class="related row">
	<div class="col-md-12">
	<h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>
	<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-striped">
	<thead>
	<tr>
<?php
			foreach ($details['fields'] as $field) {
				echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
			}
?>
		<th class="actions"></th>
	</tr>
	<thead>
	<tbody>
<?php
echo "\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
		echo "\t\t<tr>\n";
			foreach ($details['fields'] as $field) {
				echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
			}

		echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
	</tbody>
	</table>
<?php echo "<?php endif; ?>\n\n"; ?>
	</div><!-- end col md 12 -->
</div>
<?php endforeach; ?>
