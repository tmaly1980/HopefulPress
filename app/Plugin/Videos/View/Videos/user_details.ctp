<? $models = array_keys($this->request->models); $modelClass = $models[0]; ?>
<? if(empty($this->data[$modelClass]['video_id'])) {  ?>
<? } else if(!empty($this->data[$modelClass])) { ?>
<? $id = !empty($this->data[$modelClass]['id']) ? $this->data["Video"]['id'] : null; ?>

<?= $this->Form->create($modelClass, array('url'=>array('action'=>'edit',$id))); ?>
<?
$video_site = !empty($this->data[$modelClass]['video_site']) ? $this->data[$modelClass]['video_site'] : null;
$video_id = !empty($this->data[$modelClass]['video_id']) ? $this->data[$modelClass]['video_id'] : null;
$description = !empty($this->data[$modelClass]['description']) ? $this->data[$modelClass]['description'] : null;
# Generated... XXX TODO. via helper.

$strlen = 300;

$description = $this->Text->truncate($description, $strlen,array('exact'=>false));
#$this->data[$modelClass]['description'] = $description = "FOO BAR";

?>
	<?= $this->Form->hidden("id"); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<? if(!empty($parent_key)) { ?>
		<?= $this->Form->hidden($parent_key); ?>
	<? } ?>
	<?= $this->Form->hidden("video_url"); ?>
	<?= $this->Form->hidden("video_site"); ?>
	<?= $this->Form->hidden("video_id"); ?>
	<?= $this->Form->hidden("video_page_id"); ?>

	<?= $this->Form->hidden("project_id"); ?>

	<table width="100%">
	<tr>
		<td width="50%">
			<label>Preview</label>
			<?= $this->element("Videos.../Videos/embed",array('width'=>325,'video_site'=>$video_site,'video_id'=>$video_id)); ?>

			<?= $this->Form->hidden("thumbnail_url"); ?>
			<?= $this->Form->hidden("preview_url"); ?>
		</td>
		<td width="50%">

			<?= $this->Form->title(); ?>
			<?= $this->Form->input("description",array('class'=>'width95p','rows'=>8)); ?>

			<?#= $this->Form->belongsTo_input("video_category_id"); # NO NEED FOR NOW.... chrono stream is probably ok. ?>

			<div class='row'>
			<div class='col-md-6'>
				<?= $this->Form->save($id?"Update Video":"Add Video"); ?>
			</div>
			<? if(!empty($id)) { ?>
			<div class='col-md-6'>
				<?= $this->Html->delete("Remove",array('action'=>'delete',$id),array('confirm'=>"Are you sure you want to remove this video?")); ?>
			</div>
			<? } ?>
		</td>
	</tr>
	</table>



<?= $this->Form->end(); ?>

<? } ?>
