<? $id = $adoptable['Adoptable']['id']; ?>
<? $imgid = $adoptable['Adoptable']['page_photo_id']; ?>
<?  $name = $adoptable['Adoptable']['name']; ?>
<? $this->assign("page_title",  "Sponsor $name");  ?>
<? $this->start("title_controls");?>
	<?= $this->Html->back("View more about $name", array('action'=>'view',$id)); ?>
<? $this->end("title_controls");?>
<div  class='view'>

<div class='row'>
	<div class='col-md-3'>
		<?= $this->Html->image(!empty($imgid)?"/page_photos/page_photos/image/$imgid":"/rescue/images/nophoto.png",array('class'=>'width100p')); ?>
	</div>
	<div class='col-md-9'>
		<? if(!empty($adoptable['Adoptable']['sponsorship_details'])) { ?>
		<h3>Sponsorship Details</h3>
		<p>
			<?= $adoptable['Adoptable']['sponsorship_details']; ?>
		</p>
		<? } ?>
	</div>
</div>

<?= $this->requestAction("/donation/donate/$id",array('return')); ?>



</div>

