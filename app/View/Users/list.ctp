<tr class='<?= !empty($class) ? $class : "" ?>'>
	<td class='nowrap'>
		<?= $this->Html->link($user['User']['LastFirstName'], array('action'=>'edit',$user['User']['UserID'])); ?>
		<span class='visible-xs-inline visible-sm-inline'>
			<?= $this->Html->glink('email','Send Email', "mailto://".$user['User']['Email']); ?>
		</span>
	</td>
	<td class='hidden-xs hidden-sm'>
		<?= $this->Html->glink('email', $user['User']['Email'], "mailto://".$user['User']['Email']); ?>
	</td>
	<td class=''>
		<? if(!empty($user['User']['TechSupport'])) { ?>
			<div class='red bold'>TECH SUPPORT</div>
		<? } ?>
		<? if(!empty($user['User']['Admin'])) { ?>
			<div class='red bold'>ADMIN</div>
		<? } ?>
		<? if(!empty($user['User']['Processor'])) { ?>
			<div class='green bold'>Processor</div>
		<? } ?>
		<? for($i = 0; $i < count($user['Grower']); $i++) { ?><?= $i > 0 ? ",<br class='visible-xs-block visible-sm-block'/> " : ""; ?>
			<?= $this->Html->link($user['Grower'][$i]['LabelName'], array('prefix'=>null,'controller'=>'growers','action'=>'view',$user['Grower'][$i]['ContactID'])); ?><? } ?>
	</td>
	<? if(!empty($showCreated)) { ?>
	<td class=''>
		<?= date("m/d", strtotime($user['User']['created'])); ?>
	</td>
	<? } ?>
</tr>

