		<div class='social-icons'>
		<? if(!empty($nav['donationsEnabled'])) { ?>
		        <?= $this->Html->link("Donate", "/donation", array('class'=>'btn btn-warning controls')); ?>
		<? } ?>

		<? if($fb = $rescue['Rescue']['facebook_url']) { ?>
			<?= $this->Html->link($this->Html->fa("facebook fa-lg"), $fb, array('title'=>'Find us on Facebook','class'=>'btn white bluebg medium')); ?>
		<? } ?>
		<? if($tw = $rescue['Rescue']['twitter_url']) { ?>
			<?= $this->Html->link($this->Html->fa("twitter fa-lg"), $tw, array('title'=>'Find us on Twitter','class'=>'btn white tealbg medium')); ?>
		<? } ?>
		</div>
