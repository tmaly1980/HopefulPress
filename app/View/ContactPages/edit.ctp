<? $this->assign("page_title", "Edit Contact Page"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->blink("back", "View Page", array("action"=>"view")); ?>
<? $this->end(); ?>

<div class="pages form">
	<?php echo $this->Form->create('ContactPage', array('role' => 'form')); ?>


		<?php echo $this->Form->input('title', array('class' => 'h2 input-lg', 'label'=>false, 'placeholder' => 'Contact Page Title','default'=>'Contact Us'));?>
		<div class='row'>
			<?= $this->Form->input("phone", array('required'=>1,'label'=>'Main Phone Number','div'=>'col-md-4','placeholder'=>'(212) 555-1212')); ?>
			<?= $this->Form->input("alternate_phone", array('label'=>'Secondary Number','div'=>'col-md-4','placeholder'=>'(212) 555-1212')); ?>
			<?= $this->Form->input("fax", array('label'=>'Fax Number','placeholder'=>'(212) 555-3434','div'=>'col-md-4')); ?>
		</div>
		<?= $this->Form->input("email", array('required'=>1,'label'=>'Primary Email','placeholder'=>'email@domain.com','div'=>'')); ?>
		<?= $this->Form->input("address", array('required'=>1,'label'=>'Street Address','placeholder'=>'123 Main Street','div'=>'')); ?>
		<div class='row'>
			<?= $this->Form->input("city", array('required'=>1,'label'=>'City','placeholder'=>'Anytown','div'=>'col-md-4')); ?>
			<?= $this->Form->input("state", array('required'=>1,'label'=>'State/Province','placeholder'=>'','div'=>'col-md-4')); ?>
			<?= $this->Form->input("zip_code", array('required'=>1,'label'=>'Zip Code','placeholder'=>'','div'=>'col-md-4')); ?>
		</div>
		<div class='row'>
			<div class='col-md-6'>
			<?#### BORKEN= $this->Form->input("show_map",array('label'=>'Show interactive map')); ?>
			</div>
			<? /*
			<script>
			$('#map').bind('refresh', function() {
				if($('#ContactPageShowMap').is(":checked"))
				{
					var address = [
						$('#ContactPageAddress').val(),
						$('#ContactPageCity').val(),
						$('#ContactPageState').val(),
						$('#ContactPageZipCode').val()
					];
					//$('#map').loadGoogleMap
				} else {
					$('#map').html(''); // clear.

				}

			});
			$('.address').change(function() {
				$('#map').trigger('refresh');
			});
			$('#ContactPageShowMap').click(function() { // Preview map!
				$('#map').trigger('refresh');
			});
			</script>
			*/ ?>
			<div align='right' class='col-md-6'>
				<?= $this->Form->save("Update Contact Page"); ?>
			</div>
		</div>

	<?php echo $this->Form->end() ?>

	<div class='clear'></div>
	<script>
	</script>
</div>
