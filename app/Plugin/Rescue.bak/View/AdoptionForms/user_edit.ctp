<?
Configure::load("Rescue.breeds");
$breeds = Configure::read("Breeds");
$species = array_combine(array_keys($breeds),array_keys($breeds));
?>
<? $this->assign("page_title","Customize Online Adoption Form"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("View Online Adoption Form", array('action'=>'view')); ?>
<? $this->end("title_controls"); ?>

<div class='form'>
<?= $this->Form->create("AdoptionForm",array('validate'=>false)); ?>
	<?= $this->Form->title(); ?>
	<?= $this->Form->input("introduction",array('class'=>'editor'));  ?>
	<?= $this->Form->input("species",array('options'=>$species,'note'=>'Hides questions not relevent to this species')); ?>

	<h3>Custom questions</h3>
	<!-- other customizable sections, etc -->
	<?= $this->Form->input("custom_title",array('label'=>'Custom section title')); ?>

	<div id="CustomFields_template" style="display:none;">
		<?= $this->element("Rescue.../AdoptionForms/custom_field");?>
	</div>

	<div id="CustomFields">
	<? for($i=0; $i < count($this->request->data['AdoptionForm']['custom_fields']);$i++) { ?>
	<div class='CustomField'>
		<?= $this->element("Rescue.../AdoptionForms/custom_field",array('i'=>$i));?>
	</div>
	<? } ?>
	</div>
	<?= $this->Html->add("Add custom question", "javascript:void(0)",array('id'=>'AddCustomField')); ?>

	<hr/>

	<?= $this->Form->input("acknowledgment",array('label'=>'Disclaimer/Acknowledgment','class'=>'editor'));  ?>


	<div class='row'>
		<div class='col-md-6'>
			<?= $this->Html->delete("Disable Online Form", array('action'=>'disable')); ?>
		</div>
		<div class='col-md-6 right_align'>
			<?= $this->Form->save(); ?>
		</div>
	</div>

<?= $this->Form->end(); ?>
</div>
<script>
$('#AddCustomField').click(function() {
	var i = $('#CustomFields').find('.CustomField').size();
	var container = $("<div class='CustomField margin10 lightgreybg border row'></div>");
	$('#CustomFields').append(container);
	container.html($('#CustomFields_template').clone().html());
	container.find(':input').each(function() {
		var name = $(this).attr('name').replace('[_]', '[<?=$this->Form->defaultModel?>][custom_fields]['+i+']');
		$(this).attr('name',name);
		if(oldid = $(this).attr('id')) // Hiddens dont need.
		{
			var id = oldid.replace(/^_/, '<?= $this->Form->defaultModel ?>CustomField'+i);
			$(this).attr('id',id);
			// Fix labels.
			var label = container.find('label[for='+oldid+']');
			if(label && label.size())
			{
				label.attr('for',id);
			}
		}
	}); // Rename
	container.find('.FieldQuestion').attr('required','required');
	container.find('.FieldOptions').hide(); // until proper type chosen
	container.append('<div class="clear"></div>');

	// ??? add naming filter to field_name???? or will clone carry over?
});
// General behavior for all rows...
$('form').on('change', '.FieldType', function() {
	var type = $(this).val();
	var container = $(this).closest('div.CustomField');

	if(type == 'radio' || type == 'checkbox' || type == 'select')
	{
		$(container).find('.FieldOptions').show();
	} else {
		$(container).find('.FieldOptions').hide();
	}
});
$('form').on('click', '.FieldDelete', function() {
	$(this).closest('div.CustomField').remove();
});
</script>
<style>
</style>

