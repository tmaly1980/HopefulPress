	<? 
		# Thankfully, if pieces are missing, it's just blank and not an error...
		$full_address = preg_replace("/\s+/", "+", sprintf("%s, %s, %s %s, %s", 
			$eventLocation['EventLocation']['address'],
			$eventLocation['EventLocation']['address_2'],
			$eventLocation['EventLocation']['city'],
			$eventLocation['EventLocation']['state'],
			$eventLocation['EventLocation']['zip_code'],
			$eventLocation['EventLocation']['country']));
	?>
		<?= $eventLocation['EventLocation']['name'] ?><br/>
		<?= $eventLocation['EventLocation']['address'] ?><? if(!empty($eventLocation['EventLocation']['address_2'])) { ?>, <?= $eventLocation['EventLocation']['address_2'] ?><? } ?><br/>
		<? if(!empty($eventLocation['EventLocation']['city'])) { ?>
			<?= $eventLocation['EventLocation']['city'] ?><?= !empty($eventLocation['EventLocation']['state']) ? ", ".$eventLocation['EventLocation']['state'] : "" ?>
		<? } ?>
			<? if(!empty($eventLocation['EventLocation']['zip_code'])) { ?><?= $eventLocation['EventLocation']['zip_code'] ?><? } ?><br/>
		<? if(!empty($eventLocation['EventLocation']['country'])) { ?><?= $eventLocation['EventLocation']['country'] ?><br/><? } ?>
		<? if(!empty($eventLocation['EventLocation']['phone'])) { ?><?= $eventLocation['EventLocation']['phone'] ?><br/><? } ?>

		<? if(true || !empty($eventLocation['EventLocation']['link_to_map'])) { ?>
		<div>
		<a href="http://maps.google.com/maps?daddr=<?= $full_address ?>">View Map/Directions</a>
		</div>
		<? } ?>

