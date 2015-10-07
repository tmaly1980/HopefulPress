<?php
# Fix fields so displayField is first.
$oldFields = $fields;
$fields = array($displayField);
foreach($oldFields as $f) { if($f != $displayField) { $fields[] = $f; } }

printf('<? $this->assign("page_title", "%s"); ?>'."\n", $pluralHumanName); 
printf('<? $this->assign("container_class", ""); ?>'."\n");
printf('<? $this->assign("layout_main_class", ""); ?>'."\n");
printf('<? $this->start("title_controls"); ?>'."\n");
printf('<? if($this->Site->can("add", "%s")) { ?>'."\n", $currentModelName);
printf('	<?= $this->Html->add("Add %s", array("action"=>"add"),array("class"=>"btn btn-success")); ?>'."\n", $singularHumanName);
printf('<? } ?>'."\n");
printf('<? $this->end(); ?>'."\n");
?>
<div class="<?php echo $pluralVar; ?> index">

			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<thead>
					<tr>
			<?php foreach ($fields as $field) {
				if(in_array($field, array($primaryKey, 'modified','created'))) { continue; }
			?>
			<th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
			<?php } ?>
					</tr>
				</thead>
				<tbody>
			<?php
			echo "\t<?php foreach (\${$pluralVar} as \${$singularVar}) { ?>\n";
			echo "\t\t\t\t\t<tr>\n";
				foreach ($fields as $field) {
					$isKey = false;
					if(in_array($field, array($primaryKey, 'modified','created'))) { continue; }
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								$isKey = true;
								echo "\t\t\t\t\t\t\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
								break;
							}
						}
					}
					if ($isKey !== true) {
						if($field == $displayField)
						{
							echo "\t\t\t\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['{$modelClass}']['{$field}'], array('action'=>'view',\${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>&nbsp;</td>\n";
						} else {
							echo "\t\t\t\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
						}
					}
				}

			echo "\t\t\t\t\t</tr>\n";

			echo "\t\t\t\t<?php } ?>\n";
			?>
				</tbody>
			</table>

			<? echo  '<?= $this->element("Core.pager");?>'."\n"; ?>

</div><!-- end containing of content -->
