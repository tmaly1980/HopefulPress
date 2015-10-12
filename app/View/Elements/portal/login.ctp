      <ul class="nav navbar-nav navbar-right">
        <? if(empty($current_user)) { ?>
		<li>
		<div class=''>
			<?= $this->Html->blink("user", "Sign In", "/user/users/login","btn-default"); ?>
		</div>
		</li>
	<? } else { ?>
	<? if(!$this->Rescue->dedicated()) { ?>
	<li class='paddingright25'>
		<div class=''>
		<? if($myrescue = $this->Rescue->mine()){ ?>
			<?= $this->Html->blink("fa-paw", $myrescue['title'],$this->Rescue->url($myrescue),array('class'=>'btn-primary')); ?>
		<? } else if($this->Html->user("rescuer")) {  ?>
	    	<a class='btn btn-primary' href="/rescuer/rescues/add"><?= $this->Html->g("plus"); ?> Add rescue</a>
		<? } ?>
		</div>
	</li>
	<? } ?>
	<li class='dropdown'>
	<div class=''>
	    	<a href='javascript:void(0);' class='btn btn-default'>
			<?= $this->Html->g("user"); ?>
			<?= !empty($current_user['first_name']) ? $current_user['first_name'] : $current_user['email'] ?> <?= $this->Html->s("caret hidden-sm hidden-xs"); ?></a>

		<ul class='dropdown-menu dropdown-menu-right'>
	    		<li><a href="/user/users/account">User Account</a></li>
	    		<li><a href="/user/users/logout"><?= $this->Html->g("log-out"); ?> Sign Out</a></li>
		</ul>
	</div>
	</li>
	<? } ?>
      </ul>

