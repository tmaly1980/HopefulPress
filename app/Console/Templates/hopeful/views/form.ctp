<?php
printf('<? $id = !empty($this->request->data["%s"]["id"]) ? $this->request->data["%s"]["id"] : ""; ?>'."\n", $modelClass,$modelClass);
printf('<? $this->assign("page_title", $id ? "Edit %s" : "Add %s"); ?>'."\n", $singularHumanName, $singularHumanName);
printf('<? $this->assign("container_class", ""); ?>'."\n");
printf('<? $this->assign("layout_main_class", ""); ?>'."\n");
printf('<? $this->start("title_controls"); ?>'."\n");
printf('<? if(!empty($id)) { ?>'."\n");
printf('	<?= $this->Html->back("View %s", array("action"=>"view",$id)); ?>'."\n", $singularHumanName);
printf('<? } else { ?>'."\n");
printf('	<?= $this->Html->back("All %s", array("action"=>"index")); ?>'."\n", $pluralHumanName);
printf('<? } ?>'."\n");
printf('<? $this->end(); ?>'."\n");
?>

<div class="<?php echo $pluralVar; ?> form">

<?php 		echo "\t\t\t<?php echo \$this->Form->create('{$modelClass}', array('role' => 'form')); ?>\n\n"; ?>
<?php
		foreach ($fields as $field) {
			if ((strpos($action, 'add') !== false && $field == $primaryKey) || in_array($field, array('user_id','site_id'))) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
				#echo "\t\t\t\t<div class=\"form-group\">\n";
				echo "\t\t\t\t\t<?php echo \$this->Form->input('{$field}', array('class' => '', 'label'=>null, 'Xplaceholder' => '".Inflector::humanize($field)."'));?>\n";
				#echo "\t\t\t\t</div>\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\t\t\t<div class=\"form-group\">\n";
				echo "\t\t\t\t\t<?php echo \$this->Form->input('{$assocName}', array('class' => 'form-control', 'placeholder' => '".Inflector::humanize($field)."'));?>\n";
				echo "\t\t\t\t</div>\n";
			}
		}
?>
<?php
                echo  "\t\t\t\t<table width='100%'><tr><td>\n";
                printf("\t\t\t\t\t".'<?= $this->Form->save(!$id?"Create %s":"Update %s"); ?>'."\n",$singularHumanName, $singularHumanName);
                echo  "\t\t\t\t".'</td><td align="right">'."\n";
                printf( "\t\t\t\t".'<? if(!empty($id) && $this->Site->can("delete")) { ?>'."\n");
		printf("\t\t\t\t\t".'<?= $this->Html->delete("Delete %s", array("action"=>"delete",$this->data["%s"]["%s"]), array("confirm"=>"Are you sure you want to delete this %s?")); ?>'."\n", $singularHumanName, $modelClass,$primaryKey,strtolower($singularHumanName));
                echo  "\t\t\t\t".'<? } ?>'."\n";
		echo  "\t\t\t\t</td></tr></table>\n";

	echo "\t\t\t<?php echo \$this->Form->end() ?>\n\n";
	echo "\t\t\t<div class='clear'></div>\n";
	echo "\t\t\t<script>\n";
	echo "\t\t\t</script>\n";

?>
</div>
