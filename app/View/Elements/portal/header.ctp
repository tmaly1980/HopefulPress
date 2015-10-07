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
<div>
<h1>Hopeful Press: Matching adoptables with forever homes</h1>
<!--Find your next pet, list adopables, collaborate with rescuers, fosters, volunteers and transporters</h2>-->
	<ul class='nav nav-pills'>
		<li class='<?= $active == 'adopt'?"active":""?>'> <a href='/adopt' id=''>Adopt</a> </li>
		<li class='<?= $active == 'rescue'?"active":""?>'> <a href='/rescues' id=''>Rescue</a> </li>
		<!--
		<li class='<?= $active == 'foster'?"active":""?>'> <a href='/fosters' id=''>Foster</a> </li>
		<li class='<?= $active == 'transport'?"active":""?>'> <a href='/transporters' id=''>Transport</a> </li>
		<li class='<?= $active == 'volunteer'?"active":""?>'> <a href='/volunteers' id=''>Volunteer</a> </li>
		-->
	</ul>
</div>
<? if($active == 'adopt' || (empty($prefix) && in_array($controller,array('adoptables')))) { ?>
	<? echo $this->element("adoptables/search"); ?>
<? } ?>
<? if($active == 'rescue') { ?>
<div>
	<? if(!empty($rescue) && empty($prefix)) { ?>
		<?= $this->Html->back("All rescues", "/rescues/search"); ?>
		<?= $this->element("rescue/header"); ?>
	<? } ?>
</div>
<? } ?>
