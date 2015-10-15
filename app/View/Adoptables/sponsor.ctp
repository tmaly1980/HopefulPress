<? $id = $adoptable['Adoptable']['id']; ?>
<? $imgid = $adoptable['Adoptable']['adoptable_photo_id']; ?>
<?  $name = $adoptable['Adoptable']['name']; ?>
<? $this->assign("page_title",  "Sponsor $name");  ?>
<? $this->start("title_controls");?>
	<?= $this->Html->back("View more about $name", array('action'=>'view',$id)); ?>
<? $this->end("title_controls");?>
<div  class='view'>

<div class='row'>
	<div class='col-md-6'>
		<?= $this->element("PagePhotos.view",array('photoModel'=>'AdoptablePhoto','class'=>'width100p','width'=>400)); ?>
	</div>
	<div class='col-md-6 paddingtop50'>
		<? if(!empty($adoptable['Adoptable']['sponsorship_details'])) { ?>
		<h3>Sponsorship Details</h3>
		<hr/>
		<p>
			<?= $adoptable['Adoptable']['sponsorship_details']; ?>
		</p>
		<? } ?>
	</div>
</div>

<hr/>

<?= $this->requestAction("/donation/donate/$id",array('return')); ?>



</div>

