<script>$.dialogtitle("Event Location Details");</script>
<script>//j.modalfit();</script>
<? $adding = empty($this->data['EventLocation']['id']); ?>
<div class="EventLocation form width350 fit">
<?= $this->Form->create("EventLocation",array('update'=>'EventLocation','class'=>'ajax','data-update'=>'EventLocation')); ?>
	<?= $this->Form->hidden('id'); ?>
	<table width="100%" cellspacing=0>
	<tr>
		<td colspan=2 class=''>
			<?= $this->Form->input('name', array('label'=>'Location name')); ?>
			<?= $this->Form->input('address'); ?>
			<?= $this->Form->input('city'); ?>
		</td>
	</tr>
	<tr><td width="60%" class='paddingright20'>
		<?= $this->Form->input('state'); ?>
	</td><td class=''>
		<?= $this->Form->input('zip_code'); ?>
	</td></tr>
	<tr><td class='paddingright20'>
		<?= $this->Form->input('country'); ?>
	</td><td class=''>
		<?= $this->Form->input('phone'); ?>
	</td></tr>
	</table>

	<div class="clear"></div>
	<?= $this->Form->save('Save Location');#,array('modal'=>true)); ?>

<?= $this->Form->end(); ?>
</div>
