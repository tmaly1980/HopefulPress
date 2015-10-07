		<? $this->element("defs/social"); ?>
		<div class='social-icons'>
		<?= $this->fetch("social_content"); ?>
		<? if($fb = $siteDesign['SiteDesign']['facebook_url']) { ?>
			<?= $this->Html->link($this->Html->fa("facebook fa-lg"), $fb, array('title'=>'Find us on Facebook','class'=>'btn white bluebg medium')); ?>
		<? } ?>
		<? if($tw = $siteDesign['SiteDesign']['twitter_url']) { ?>
			<?= $this->Html->link($this->Html->fa("twitter fa-lg"), $tw, array('title'=>'Find us on Twitter','class'=>'btn white tealbg medium')); ?>
		<? } ?>
		</div>
