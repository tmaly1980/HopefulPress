    <div class="navbar navbar-default navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
	  	<img src='/www/images/logo-small.png'/>
	  	Hopeful Press Support
  	  </a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          	<li><a href="/questions">Questions</a></li>
          	<li><a href="/tickets">Issues</a></li>
          	<li><a href="/forum">Discussions</a></li>
	  </ul>
          <ul class="nav navbar-nav navbar-right">
          	<li class='dropdown toggle'>
			<? if($this->Html->me()) { ?>
	    		<a href='javascript:void(0);' class=''>
				<?= !empty($current_user['page_photo_id']) ? $this->Html->image("/page_photos/page_photos/thumb/{$current_user['page_photo_id']}/24x24") : $this->Html->g('user'); ?> 
				<?= $current_user['first_name'] ?> <?= $this->Html->s("caret hidden-sm hidden-xs"); ?></a>
			<ul class='dropdown-menu dropdown-menu-right right_align'>
	    			<li><a href="/user/users/account">Account</a></li>
	    			<li><a href="/user/users/logout"><?= $this->Html->g("log-out"); ?> Sign Out</a></li>
			</ul>
			<? } else { ?>
	    			<li><a href="/user/users/login"><?= $this->Html->g("user"); ?> Sign In</a></li>
			<? } ?>
		</li>
          </ul>
      </div><!--/.nav-collapse -->
      </div>
    </div>
<? if(!empty($this->request->params['manager'])) { ?>
<div class='padding5 navbar'>
<ul class='nav navbar-nav'>
	<li> <?= $this->Html->link("Manager", "/manager"); ?>
	<li> <?= $this->Html->link("Marketing Homepage", "/manager/tracker/tracker/page_view/Marketing?href=/"); ?>
</ul>
</div>
<? } ?>
