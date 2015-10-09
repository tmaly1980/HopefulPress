<div class='row'>
<? if(empty($adoptables)) { ?>
<div class='nodata'>
	No available adoptables matched your criteria.
</div>
<?  } ?>
<? foreach($adoptables as $adoptable) { 
	$imgid = !empty($adoptable['Adoptable']['adoptable_photo_id']) ? $adoptable['Adoptable']['adoptable_photo_id'] : null;
	# Default to first in photos list
	if(empty($imgid) && !empty($adoptable['Photos'][0]['id']))
	{
		$imgid = $adoptable['Photos'][0]['id'];
	}

?>
<div class='col-md-3 col-sm-6 col-xs-6 height275 paddingbottom25'>
	<div class='height200 center_align autoheight-xs'>
	<?= $this->Html->link($this->Html->image(!empty($imgid)?array('controller'=>'adoptable_photos','action'=>'thumb',$imgid,'200x200',1,'rescue'=>$adoptable['Rescue']['hostname']):"/rescue/images/nophoto.png", array('class'=>'maxwidth100p border')), array('controller'=>'adoptables','action'=>'view','id'=>$adoptable['Adoptable']['id'],'rescue'=>$rescuename)); ?>
	</div>
	<div class='center_align'>
		<?= $this->Html->link($adoptable['Adoptable']['name'], array('controller'=>'adoptables','action'=>'view','id'=>$adoptable['Adoptable']['id'],'rescue'=>$rescuename), array('class'=>'bold medium')); ?>
		<br/>
			<b><?= $this->Time->timeago($adoptable['Adoptable']['created']); ?></b>
		<br/>
		<?= $this->element("../Adoptables/item_stats", array('adoptable'=>$adoptable)); ?>
		<? if(empty($rescuename) && !empty($adoptable['Rescue']['city'])) { ?>
		<div>
			<?= $adoptable['Rescue']['city'] ?>, <?= $adoptable['Rescue']['state'] ?>
			<!-- # XXX MILES AWAY FROM SEARCH SPECS -->
		</div>
		<? } ?>
	</div>
</div>
<? } ?>
</div>
<div class='clear'></div>

