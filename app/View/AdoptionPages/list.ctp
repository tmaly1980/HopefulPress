<!-- we still want this to show up if empty, so resort/dragdrop works between one list and an empty one -->
<div id="AdoptionPages" class='pagelist minheight50'>
	<? 
	foreach ($pages as $page) { 
		echo $this->element("../AdoptionPages/listitem", array('page'=>$page));
	}
	?>
</div>
