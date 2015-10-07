<?
if(!empty($rescuename)) { # Already in one, just show browse all...
?>
<ul class='breadcrumb'>
	<li>
		<?= $this->Html->back("Browse All Rescues",array('controller'=>'rescues','action'=>'search'),array('class'=>'btn btn-default')); ?>
	</li>
	<li class='bold'>
		<?= $rescueCount ?> rescues nearby
		<!-- nearby me or nearby CURRENT rescue? OR ALL rescues? -->
	</li>
</ul>
<?
} else {# No rescue chosen yet.
	$browse = $this->Html->link("Browse All Rescues",array('controller'=>'rescues','action'=>'search'),array('class'=>'btn btn-default')); 
	$heading = "Find a local rescue: $rescueCount rescues nearby";
	$sortby=array('distance'=>'Closest','title_asc'=>'Alphabetical');
	echo $this->element("portal/search_bar",array('model'=>'Rescue','sortby'=>$sortby, 'browse'=>$browse,'heading'=>$heading));
}
