<!-- LAYOUT=Rescue.rescue -->
<? 
if($admin_controls_before = $this->fetch("admin_controls_before")) { 
	$this->assign("title_controls_before", $admin_controls_before); 
} 
if($admin_controls = $this->fetch("admin_controls")) { 
	$this->assign("title_controls", $admin_controls); 
} 
?>
<? if(empty($XXXpreview)) { ?>
<?  $this->prepend("before_maincol"); # ALL ADMIN STUFF GETS MENTIONED HERE, SO main.ctp CAN RELOAD PREVIEWS OF PAZGE ?>
	<?= !$this->fetch("login_disabled") ? $this->element("admin/login") : null; ?>
	<?= $this->fetch("before_maincol_postlogin"); ?>
<? $this->end(); ?>
<? if($this->Html->me()) { # Admin wrapper content ?>
<? $this->start("prepend_body"); ?>
	<input type='checkbox' id='leftnav-trigger' class='nav-trigger push'/>
	<input type='checkbox' id='rightnav-trigger' class='nav-trigger'/>
<? $this->end("prepend_body"); ?>
<? $this->start("prepend_maincol"); ?>
	<?= $this->element("admin/notices"); ?>
<? $this->end("prepend_maincol"); ?>
<?  $this->start("before_maincol"); # LEFT ADMIN BAR ?>
<? if(!$this->fetch("leftnav_disabled")) { ?>
	<label for="leftnav-trigger">
		<?= $this->Html->g("menu-hamburger btn btn-primary"); ?>
	</label>
	<div id='leftnav' class='slidenav slidenav-left sidebar-nav'>
		<?= $this->element("admin/nav"); ?>
	</div>
	<script>
	$(window).resize(function() {
		// on larger screens, check (open) left/right navs
		var width = $(window).width();
		if(width > 968)
		{
			$('#leftnav-trigger').attr('checked','checked');
			$('#rightnav-trigger').attr('checked','checked');
		} else {
			$('#leftnav-trigger').removeAttr('checked');
			$('#rightnav-trigger').removeAttr('checked');
		}
	});
	$(document).ready(function() {
		$(window).resize();
	});
	$('#leftnav > .navbar').slimscroll({height: 'auto', color: '#ddd'});
	//XXX TODO implement some refresh of scrollbar when window resized - doesnt work despite trying...
	/*
	$(document).on('click', function(e) { // close on click outside - but kinda annoying!
		if($(e.target).closest('#leftnav-trigger, label[for=leftnav-trigger]').size()) { return true; }
		if(!$(e.target).closest('#leftnav').size() && $('#leftnav-trigger:checked').size()) // clicked outside of nav.
		{
			$('#leftnav-trigger').prop('checked',''); // close
			// DOES trigger click....
		}
		return true;
	});
	*/
	</script>
<? } ?>
<? $this->end("before_maincol");  ?>
<? } ?>
<? } # NOT stripped down preview ?>

<? $this->start("layout_footer"); ?>
	<?= $this->element("footer"); ?>
<? $this->end("layout_footer"); ?>
<?= $this->element("../Layouts/components"); # Header/nav/etc ?>
<?= $this->element("Core.../Layouts/core"); ?>
