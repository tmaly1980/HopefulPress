<!-- we still want this to show up if empty, so resort/dragdrop works between one list and an empty one -->
<div id="<?= !empty($id) ? $id : null; ?>" class='pagelist'>
	<? 
	foreach ($pages as $page) { 
		echo $this->element("Rescue.../FosterPages/listitem", array('page'=>$page));
	}
	?>
</div>
