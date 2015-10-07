		<?= $eventContact['EventContact']['name'] ?><br/>
		<? if(!empty($eventContact['EventContact']['phone'])) { ?><?= $eventContact['EventContact']['phone'] ?><br/><? } ?>
		<? if(!empty($eventContact['EventContact']['email'])) { ?><a href="mailto:<?= $eventContact['EventContact']['email'] ?>"><?= $eventContact['EventContact']['email'] ?></a><br/><? } ?>
		<? if(!empty($eventContact['EventContact']['comments'])) { ?>
		<p>
			<?= $eventContact['EventContact']['comments'] ?>
		</p>
		<? } ?>

