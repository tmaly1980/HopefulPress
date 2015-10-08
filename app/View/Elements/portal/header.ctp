<?
$prefix = !empty($this->request->params['prefix']) ? $this->request->params['prefix'] : null;
$plugin = !empty($this->request->params['plugin']) ? $this->request->params['plugin'] : null;
$controller = !empty($this->request->params['controller']) ? $this->request->params['controller'] : null;

$active = 'adopt';
if(!empty($rescuename) || $controller == 'rescues') { $active = 'rescue'; }
if($plugin == 'transport') { $active = 'transport'; }
if($plugin == 'foster') { $active = 'foster'; }
if($plugin == 'volunteer') { $active = 'volunteer'; }


# check for other tabs.

?>
<nav class="navbar margin0 navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">
		<img src='/www/images/paw-logo.png' class='left paddingright10'/>
		Hopeful Press
      </a>
    </div>
<div class="collapse navbar-collapse" id="header-navbar">
      <ul class="nav navbar-nav">
		<li class='<?= $active == 'adopt'?"active":""?>'> <a href='/adopt' id=''>Adopt</a> </li>
		<li class='<?= $active == 'rescue'?"active":""?>'> <a href='/rescues' id=''>Rescue</a> </li>
		<!--
		<li class='<?= $active == 'foster'?"active":""?>'> <a href='/fosters' id=''>Foster</a> </li>
		<li class='<?= $active == 'transport'?"active":""?>'> <a href='/transporters' id=''>Transport</a> </li>
		<li class='<?= $active == 'volunteer'?"active":""?>'> <a href='/volunteers' id=''>Volunteer</a> </li>
		-->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <? if(empty($current_user)) { ?>
		<li>
			<?= $this->Html->blink("user", "Sign In", "/user/users/login","btn-default"); ?>
		</li>
	<? } else { ?>
	<li class='dropdown'>
	    	<a href='javascript:void(0);' class='btn btn-default'>
			<?= !empty($current_user['first_name']) ? $current_user['first_name'] : 'No name' ?> <?= $this->Html->s("caret hidden-sm hidden-xs"); ?></a>

		<ul class='dropdown-menu dropdown-menu-right'>
	    		<li><a href="/user/users/account">User Account</a></li>
			<? if(($myrescue = $this->Html->user("Rescue")) && !empty($myrescue['id'])) { ?>
	    		<li><a class='bold' href="/rescue/<?= $myrescue['hostname'] ?>"><?= $this->Html->fa("paw"); ?> <?=$myrescue['title'] ?></a></li>
	    		<li><a href="/rescuer/rescue/<?= $myrescue['hostname'] ?>/edit"><?= $this->Html->g("cog"); ?> My rescue's profile</a></li>
			<? } else if($this->Html->user("rescuer")) {  ?>
	    		<li><a href="/rescuer/rescues/add"><?= $this->Html->g("plus"); ?> Add rescue</a></li>
			<? } ?>
	    		<li><a href="/user/users/logout"><?= $this->Html->g("log-out"); ?> Sign Out</a></li>
		</ul>
	</li>
	<? } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<? if($active == 'adopt' || (empty($prefix) && in_array($controller,array('adoptables')))) { ?>
	<?= !$this->fetch("search_disabled") ? $this->requestAction("/adoptables/search_bar",array('return')) : null; # We get redirect loop if we omit  prefix, since this shows up on user login page ?>
<? } ?>
<? if($active == 'rescue') { 
	if(empty($prefix))
	{
		echo $this->requestAction(array('prefix'=>null,'plugin'=>null,'controller'=>'rescues','action'=>'search_bar','rescue'=>$rescuename),array('return')); 
	}

# Someone may have stumbled upon some listings on a rescue site, and they want to look for others..
# MAKE SEARCH SOMEWHAT OBVIOUS EVEN ON RESCUE PAGES.... why not ALL?

# ??? question to ask users...
# If inside an adoptable, switch to 'Adopt', and give little blurb about specific rescue, link to profile/page
# ie when browsing adoptables, dont distract with rescue design/nav/etc...
# *** perhaps have a way to list adoptables in a global (unbranded) context vs a rescue-specific (branded) context....
# In that case, hide 'search' bar as well. but offer a way to find more or go global
# ie go off '$rescuename' rather than $adoptable['Rescue']['hostname']....
/*

HEADER DONE BY LAYOUT now...
?>
<div>

	<? if(!empty($rescue) && empty($prefix)) { ?>
		<?= $this->element("rescue/header"); ?>
	<? } ?>
</div>
<? */ } ?>
