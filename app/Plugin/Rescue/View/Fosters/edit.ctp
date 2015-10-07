<? $id = !empty($this->request->data['Foster']['id']) ? $this->request->data['Foster']['id'] : null; ?>
<? if($this->request->controller == 'fosters') { ?>
	<? $this->assign("page_title", $fosterForm['FosterForm']['title']); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Foster Information", array('admin'=>false,'action'=>'index')); ?>
	<? if($this->Html->can_edit()) { ?>
			<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'foster_forms','action'=>'edit')); ?>
	<? } ?>
<? $this->end("title_controls"); ?>
<? } else { ?>
	<!--<h3><?= $fosterForm['FosterForm']['title']; ?></h3>-->
	<? if($this->Html->can_edit()) { ?>
	<div class='right'>
		<?= $this->Html->edit("Customize Form", array('admin'=>1,'controller'=>'volunteer_forms','action'=>'edit'),array('short'=>false)); ?>
	</div>
	<? } ?>
<? } ?>
<!-- make it long, then give them the control to remove sections if they think it's too long and don't need certain parts -->
<!-- just go with what I have and ask if anything missing, that should add... -->
<?#= $this->element("Core.js/formbuilder"); ?>
<? /* $this->start("title_controls"); ?>
	<!--
	<div class='btn-group'>
		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
			Add Field
			<span class='caret'></span>
		</button>
		<ul class='dropdown-menu' role='menu'>
			<li><a href='javascript:void(0)' data-type='text'><?= $this->Html->g("text"); ?> Text</a></li>
			<li><a href='javascript:void(0)' data-type='textarea'><?= $this->Html->g("align-justify"); ?> Text area (multiline)</a></li>
			<li><a href='javascript:void(0)' data-type=''><?= $this->Html->g("align-justify"); ?> Text area (multiline)</a></li>
		</ul>
	</div>

	<?= $this->Html->link("Save Form", "javascript:void(0)", array('id'=>'SaveForm')); ?>
	<?= $this->Html->link("Save Form", "javascript:void(0)", array('id'=>'SaveForm')); ?>
	-->

<? $this->end("title_controls"); */ ?>

<div class='form '>
	<p id='FosterForm_introduction'>
		<?= nl2br($fosterForm['FosterForm']['introduction']) ?>
	</p>

	<?= $this->Form->create("Foster"); ?>
		<?= $this->Form->hidden("id"); ?>
		<?= $this->element("Rescue.forms/admin_status"); ?>
		<?= $this->element("Rescue.forms/about"); ?>
		<?= $this->element("Rescue.forms/home"); ?>

	<? if(!empty($fosterForm['FosterForm']['acknowledgment'])) { ?>
	<h3>Acknowledgment/Disclaimer</h3>
	<? } ?>

	<div id='FosterForm_acknowledgment'>
		<?= $fosterForm['FosterForm']['acknowledgment'] ?>
	</div>

	<hr/>

		<?= $this->Form->save(!empty($id)?"Update Application":"Submit Application",array('cancel'=>false)); ?>

		<?= $this->Form->end(); ?>
	</div>
</div>
<script>
<? /* if($this->Html->can_edit()) { ?>
	//$('#page_title').inline_edit({prefix: "user", link: '', inline:true, after: true, plugin: "rescue", model: 'FosterForm',field:'title'});
	$('#FosterForm_introduction').inline_edit({prefix: "user", plugin: "rescue", link: 'Add introduction/Edit introduction'}); 
	$('#FosterForm_acknowledgment').inline_edit({prefix: "user", plugin: "rescue", link: 'Add acknowledgement disclaimer/Edit acknowledgement disclaimer', type: 'mce'}); 
<? } */ ?>
</script>
<script>
$('#VolunteerStatus').change(function() {
	var status  = $(this).val();
	if(status == 'Active') {
		$('#InviteCheckbox').show();
	} else {
		$('#InviteCheckbox').hide();
	}
});
</script>
