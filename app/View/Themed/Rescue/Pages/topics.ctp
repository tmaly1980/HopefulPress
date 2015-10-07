<?
# Probably need to integrate custom default image with PagePhoto

# FOR NOW,  JUST DO GRAPHICS, DO CUSTOM  IMAGES LATER!!!!
$rescue = $this->Site->get("rescue_enabled");

$adopt_title = !empty($rescueHomepage['RescueHomepage']['adopt_title']) ? $rescueHomepage['RescueHomepage']['adopt_title'] : ($rescue?"Adopt":"Sponsor");
$adopt_img = !empty($rescueHomepage['RescueHomepage']['adopt_photo_id']) ? $this->Html->image("/page_photos/page_photos/thumb/".$rescueHomepage['RescueHomepage']['adopt_photo_id']) : $this->Html->fa("paw ");
$adopt_summary = !empty($rescueHomepage['RescueHomepage']['adopt_summary']) ? $rescueHomepage['RescueHomepage']['adopt_summary'] : null;

$donate_title = !empty($rescueHomepage['RescueHomepage']['donate_title']) ? $rescueHomepage['RescueHomepage']['donate_title'] : "Donate";
$donate_img = !empty($rescueHomepage['RescueHomepage']['donate_photo_id']) ? $this->Html->image("/page_photos/page_photos/thumb/".$rescueHomepage['RescueHomepage']['donate_photo_id']) : $this->Html->fa("gift ");
$donate_summary = !empty($rescueHomepage['RescueHomepage']['donate_summary']) ? $rescueHomepage['RescueHomepage']['donate_summary'] : null;

$volunteer_title = !empty($rescueHomepage['RescueHomepage']['volunteer_title']) ? $rescueHomepage['RescueHomepage']['volunteer_title'] : "Volunteer";
$volunteer_img = !empty($rescueHomepage['RescueHomepage']['volunteer_photo_id']) ? $this->Html->image("/page_photos/page_photos/thumb/".$rescueHomepage['RescueHomepage']['volunteer_photo_id']) : $this->Html->fa("heart ");
$volunteer_summary = !empty($rescueHomepage['RescueHomepage']['volunteer_summary']) ? $rescueHomepage['RescueHomepage']['volunteer_summary'] : null;

$foster_title = !empty($rescueHomepage['RescueHomepage']['foster_title']) ? $rescueHomepage['RescueHomepage']['foster_title'] : "Foster";
$foster_img = !empty($rescueHomepage['RescueHomepage']['foster_photo_id']) ? $this->Html->image("/page_photos/page_photos/thumb/".$rescueHomepage['RescueHomepage']['foster_photo_id']) : $this->Html->fa("home ");
$foster_summary = !empty($rescueHomepage['RescueHomepage']['foster_summary']) ? $rescueHomepage['RescueHomepage']['foster_summary'] : null;

?>
<div id='topics' class='row row-centered margintop25'>
	<? if(!empty($nav['adoptionEnabled']) || $this->Html->can_edit()) { ?>
	<div class='col-md-3 padding0 col-centered'>
	<div class='<?= empty($nav['adoptionEnabled']) ? "dashed margin5 alert alert-info" : "widget" ?>'>
	<div>
		<h4 class='center_align' id="RescueHomepage_AdoptTitle">
			<?= $adopt_title ?>
		</h4>
		<div class='clear'></div>
		<? if(empty($nav['adoptionEnabled'])) { # Can edit ?>
			<? if($rescue) { ?>
				There are no adoptables or adoption pages created yet.
				<?= $this->Html->add("Add adoption info", "/adoption"); ?>
			<? } else { ?>
				There are no animals or sponsorship pages created yet.
				<?= $this->Html->add("Add sanctuary info", "/sanctuary"); ?>
			<? } ?>
		<? } else  { ?>
		<?= $this->Html->link($adopt_img, $rescue?"/adoption":"/sanctuary",array('class'=>'block font64 center_align')); ?>
		<p  id="RescueHomepage_AdoptSummary">
			<?= $adopt_summary ?>
		</p>
		<? if(empty($editable)) { ?>
		<div align='center'>
			<? if($rescue) { ?>
				<?= $this->Html->link("Browse Adoptables", "/adoption", array('class'=>'btn theme')); ?>
			<? } else { ?>
				<?= $this->Html->link("Browse Animals", "/sanctuary", array('class'=>'btn theme')); ?>
			<? } ?>
		</div>
		<? } ?>
		<? } ?>
	</div>
	</div>
	</div>
	<? } ?>

	<? if(!empty($nav['donationsEnabled']) || $this->Html->can_edit()) { ?>
	<div class='col-md-3 padding0 col-centered'>
	<div class='<?= empty($nav['donationsEnabled']) ? "dashed margin5 alert alert-info" : "widget" ?>'>
	<div>
		<h4 class='center_align' id="RescueHomepage_DonateTitle">
			<?= $donate_title ?>
		</h4>
		<div class='clear'></div>
		<? if(empty($nav['donationsEnabled'])) { ?>
				Donations are not currently enabled.
				<?= $this->Html->add("Enable donations", "/donate"); ?>
		<? } else { ?>
		<?= $this->Html->link($donate_img, "/donate",array('class'=>'block font64 center_align')); ?>
		<p  id="RescueHomepage_DonateSummary">
			<?= $donate_summary ?>
		</p>
		<? if(empty($editable)) { ?>
		<div align='center'>
			<?= $this->Html->link("Donate Now", "/donate", array('class'=>'btn theme')); ?>
		</div>
		<? } ?>

		<? } ?>

	</div>
	</div>
	</div>
	<? } ?>

	<? if(!empty($nav['volunteerEnabled']) || $this->Html->can_edit()) { ?>
	<div class='col-md-3 padding0 col-centered'>
	<div class='<?= empty($nav['volunteerEnabled']) ? "dashed margin5 alert alert-info" : "widget" ?>'>
	<div>
		<h4 class='center_align' id="RescueHomepage_VolunteerTitle">
			<?= $volunteer_title ?>
		</h4>
		<div class='clear'></div>
		<? if(empty($nav['volunteerEnabled'])) { ?>
				There are no volunteer pages created yet.
				<?= $this->Html->add("Enable volunteer page", "/volunteer"); ?>
		<? } else { ?>
		<?= $this->Html->link($volunteer_img, "/volunteer",array('class'=>'block font64 center_align')); ?>
		<p  id="RescueHomepage_VolunteerSummary">
			<?= $volunteer_summary ?>
		</p>
		<? if(empty($editable)) { ?>
		<div align='center'>
			<?= $this->Html->link("Volunteer", "/volunteer", array('class'=>'btn theme')); ?>
		</div>
		<?}?>
		<? } ?>
	</div>
	</div>
	</div>
	<? } ?>

	<? if($rescue && (!empty($nav['fosterEnabled']) || $this->Html->can_edit())) { ?>
	<div class='col-md-3 padding0 col-centered'>
	<div class='<?= empty($nav['fosterEnabled']) ? "dashed margin5 alert alert-info" : "widget" ?>'>
	<div>
		<h4 class='center_align' id="RescueHomepage_FosterTitle">
			<?= $foster_title ?>
		</h4>
		<div class='clear'></div>
		<? if(empty($nav['fosterEnabled'])) { ?>
				There are no foster pages/forms created yet.
				<?= $this->Html->add("Enable foster page", "/foster"); ?>
		<? } else { ?>
		<?= $this->Html->link($foster_img, "/foster",array('class'=>'block font64 center_align')); ?>
		<p  id="RescueHomepage_FosterSummary">
			<?= $foster_summary ?>
		</p>
		<? if(empty($editable)) { ?>
		<div align='center'>
			<?= $this->Html->link("Foster", "/foster", array('class'=>'btn theme')); ?>
		</div>
		<? } ?>

		<? } ?>

	</div>
	</div>
	</div>
	<? } ?>
</div>
<? if(!empty($editable) && $this->Html->can_edit()) { ?>
<script>
<? if(!empty($nav['adoptionEnabled'])) { ?>
$('#RescueHomepage_AdoptTitle').inline_edit({plugin: "rescue", prefix: "admin", link: '', inline: 'after'});
$('#RescueHomepage_AdoptSummary').inline_edit({plugin: "rescue", prefix: "admin", link: 'Add summary/Edit summary', after: true, inline: false});
<? } ?>

<? if(!empty($nav['donationsEnabled'])) { ?>
$('#RescueHomepage_DonateTitle').inline_edit({plugin: "rescue", prefix: "admin", link: '', inline: 'after'});
$('#RescueHomepage_DonateSummary').inline_edit({plugin: "rescue", prefix: "admin", link: 'Add summary/Edit summary', after: true, inline: false});
<? } ?>
<? if(!empty($nav['fosterEnabled'])) { ?>
$('#RescueHomepage_FosterTitle').inline_edit({plugin: "rescue", prefix: "admin", link: '', inline: 'after'});
$('#RescueHomepage_FosterSummary').inline_edit({plugin: "rescue", prefix: "admin", link: 'Add summary/Edit summary', after: true, inline: false});
<? } ?>
<? if(!empty($nav['volunteerEnabled'])) { ?>
$('#RescueHomepage_VolunteerSummary').inline_edit({plugin: "rescue", prefix: "admin", link: 'Add summary/Edit summary', after: true, inline: false});
$('#RescueHomepage_VolunteerTitle').inline_edit({plugin: "rescue", prefix: "admin", link: '', inline: 'after'});
<? } ?>

</script>
<?  } ?>
