<? $this->assign("page_title", $adoptable['Adoptable']['name']); ?>
<? $imgid  = !empty($adoptable['Adoptable']['adoptable_photo_id']) ? $adoptable['Adoptable']['adoptable_photo_id']  : null; ?>
<? if(empty($imgid) && !empty($adoptable['Photos'][0]['id'])) { $imgid = $adoptable['Photos'][0]['id'];  } ?>
<div class='view'>
<div class='row'>
	<div class='col-md-6 center_align'>
		<?
			$large_imgurl = !empty($imgid)?array('controller'=>'adoptable_photos','action'=>'original',$imgid):"/images/nophoto.png";
			$imgurl = !empty($imgid)?array('controller'=>'adoptable_photos','action'=>'image',$imgid,'600x600',1):"/images/nophoto.png";
			$image = $this->Html->image($imgurl,array('class'=>'maxwidth100p maxheight500'));
		?>
		<?= !empty($imgid) ? $this->Html->link($image,$large_imgurl,array('class'=>'lightbox','title'=>$adoptable['Adoptable']['name'])) : $image; ?>
		<? $this->assign("og_image_single",true); ?>
		<? $this->Html->og_image(array('controller'=>'adoptable_photos','action'=>'image',$imgid)); ?>
	</div>
	<div class='col-md-6'>
		<div class='stats'>
		<? if(!empty($adoptable['Adoptable']['breed'])) { ?>
		<span>
				<?= $adoptable['Adoptable']['breed'] ?>
				<? if (!empty($adoptable['Adoptable']['mixed_breed'])) { ?>
					<? if(!empty($adoptable['Adoptable']['breed2'])) { ?>
						/ <?= $adoptable['Adoptable']['breed2'] ?>
					<? } else { ?>
					mix
					<? } ?>
				<? } ?>
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['gender'])) { ?>
		<span>
				<?= $adoptable['Adoptable']['gender']; ?>
				<? if(!empty($adoptable['Adoptable']['neutered_spayed'])) { ?>
					(<?= $adoptable['Adoptable']['gender'] == 'Male' ? "Neutered" : "Spayed" ?>)
				<? } ?>
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['birthdate'])) { ?>
		<span>
			<?= $this->Time->age($adoptable['Adoptable']['birthdate']); ?> 
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['age_group'])) { ?>
		<span>
			<?= $adoptable["Adoptable"]['age_group']; ?>
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['child_friendly'])) { ?>
		<span>
			Child-friendly
			<?= !empty($adoptable['Adoptable']['minimum_child_age']) ?
				"({$adoptable['Adoptable']['minimum_child_age']} and up)" : "" ?>
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['cat_friendly'])) { ?>
		<span>
			Cat-friendly
		</span>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['dog_friendly'])) { ?>
		<span>
			Dog-friendly
		</span>
		<? } ?>
		</div>

		<hr/>
		<? if(!empty($adoptable['Adoptable']['biography']) || !empty($adoptable['Adoptable']['special_needs'])){ ?>
			<h3>About <?= $adoptable['Adoptable']['name'] ?></h3>
			<div class=''>
				<?= nl2br($adoptable['Adoptable']['biography']); ?>
			</div>
			<? if(!empty($adoptable['Adoptable']['special_needs'])) { ?>
			<h3>Special Needs:</h3>
			<div class=''>
				<?= nl2br($adoptable['Adoptable']['special_needs']); ?>
			</div>
			<? } ?>
		<hr/>
		<? } ?>

		<?= $this->fetch("post_details_content"); ?>
		<hr/>

		<table class='table'>
		<? if(!empty($adoptable['Adoptable']['weight_lbs']))  { ?>
		<tr>
			<th>Current Weight:</th>
			<td><?= $adoptable['Adoptable']['weight_lbs'] ?> lbs</td>
		</tr>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['weight_lbs']))  { ?>
		<tr>
			<th>Adult Size:</th>
			<td><?= $adoptable['Adoptable']['adult_size'] ?></td>
		</tr>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['energy_level']))  { ?>
		<tr>
			<th>Energy Level:</th>
			<td><?= $adoptable['Adoptable']['energy_level'] ?></td>
		</tr>
		<? } ?>
		<? if(!empty($adoptable['Adoptable']['primary_color']))  { ?>
		<tr>
			<th>Color:</th>
			<td>
				<?= !empty($adoptable['Adoptable']['primary_color']) ? $adoptable['Adoptable']['primary_color'] : "Other" ?>
				<?= !empty($adoptable['Adoptable']['secondary_color']) ? " / " .$adoptable['Adoptable']['secondary_color'] : "" ?>
			</td>
		</tr>
		<? } ?>
		<? if($adoptable['Adoptable']['status'] != 'Adopted' && !empty($adoptable['Adoptable']['adoption_cost'])) { ?>
		<tr>
			<th>Adoption Cost:</th>
			<td><?= sprintf('$%u', $adoptable['Adoptable']['adoption_cost']);  ?></td>
		</tr>
		<? } ?>
		</table>


	</div>
</div>

<style>
.stats
{
	font-weight: bold;
	font-size: 1.1em;
}

.stats span
{
	text-align: center;
	border-right: solid #000 1px;
	padding: 0px 5px;
}
.stats span:last-child
{
	border-right: none;
}
</style>
