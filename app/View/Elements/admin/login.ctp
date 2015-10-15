<? if(!empty($this->request->query['no_controls'])) { return; } # Thumbgen?>
<div id='login' class='right zindex100'>
<? if(!$this->Html->me()) { ?>
	<div class='right'>
		<?= $this->Html->blink("user", "Sign In", "/user/users/login","btn-default"); ?>
	</div>
<? } else { ?>
	    <div class='right'>
	    	<?= $this->Html->link($this->Html->fa("life-ring"). " HELP", "http://www.{$default_domain}/contact?HOPEFULPRESS=".session_id(), array('class'=>'bold orange btn btn-default','target'=>'_new')); ?>
	    	<?#= $this->Html->link($this->Html->fa("life-ring"). " HELP", "http://support.{$default_domain}/?HOPEFULPRESS=".session_id(), array('class'=>'bold orange btn btn-default','target'=>'_new')); ?>
	    </div>
            <div class='right dropdown toggle'>
	    	<a href='javascript:void(0);' class='btn btn-default'>
			<?= (false && !empty($current_user['page_photo_id'])) ? $this->Html->image("/page_photos/page_photos/thumb/{$current_user['page_photo_id']}/24x24") : $this->Html->g('user'); ?> 
			<?= $current_user['first_name'] ?> <?= $this->Html->s("caret hidden-sm hidden-xs"); ?></a>
		<ul class='dropdown-menu dropdown-menu-right right_align'>
	    		<li><a href="/user/users/account">Account</a></li>
			<? /* if($this->Site->get("webmail_enabled") && ($domain = $this->Site->get("domain"))) { ?>
	    			<li><a href="http://mail.<?=$domain ?>/">Web Mail</a></li>
			<? } */ ?>
	    		<li><a href="/user/users/logout"><?= $this->Html->g("log-out"); ?> Sign Out</a></li>

		</ul>
	    </div>
<? } ?>
<div class='clear'></div>
</div>

