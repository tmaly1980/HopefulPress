<? 
Configure::load("Stripe.billing");
$plans = Configure::read("Billing.plans");
?>
<? $this->assign("page_title", "Change your rescue account plan"); ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->back("Cancel Account Changes",array('rescuer'=>1,'action'=>'edit')); ?>
<? $this->end(); ?>
<? 
$plan= $rescue['Rescue']['plan']; 
if(empty($plan)) { $plan  = 'free'; }
?>
<div class='row index'>
	<div class='col-md-8'>
		<table class='table table-striped'>
		<tr>
			<td>
				<h4 class='bold'>FREE Shared Account</h4>
				<div class='margintop10 marginbottom25 '>
					<ul>
					<li>FREE personal homepage for your rescue, <b>http://<?=$default_domain?>/rescue/YourRescue</b>
					<li>Up to 10 FREE adoptable listings with contact forms for adoptions, volunteering and fostering</li>
					<li>Grant access for up to 5 volunteers/fosters to add updates to your rescue page and manage adoptable listings
					</ul>
				</div>
			</td>
			<td class=''>
				<b>FREE</b>
				<br/>
				<br/>
				<? if($plan == 'free') {?>
					<i>Current Plan</i>
				<? } else { ?>
					<?= $this->Html->add("Select plan",array('rescuer'=>1,'action'=>'upgrade','free')); ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td>
				<h4 class='bold'>Basic Account</h4>
				<div class='margintop10 marginbottom25 '>
					Everything with the free account, plus:
					<ul>
					<li>Up to 25 adoptable listings 
					<li>Rescue page and adoptable listing access for up to 10 volunteers
					</ul>
				</div>
			</td>
			<td class=''>
				<b><?= sprintf("\$%u/mo or \$%u/yr (%u%% off)", ($monthly=$plans['basic']['amount']/100), ($yearly=$plans['basic_yearly']['amount']/100),
					($monthly-$yearly/12)/($monthly)*100); ?></b>
				<br/>
				<br/>
				<? if($plan == 'basic') {?>
					<i>Current Plan</i>
				<? } else { ?>
					<?= $this->Html->add("Select plan",array('rescuer'=>1,'action'=>'upgrade','basic')); ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td>
				<h4 class='bold'>Dedicated Account</h4>
				<div class='margintop10 marginbottom25 '>
					Everything with the basic plan plus:
					<ul>
					<li>Your own private (banner-free) rescue website address, either <b>http://YourRescue.<?=$default_domain?>/</b> or <b>http://YourRescue.org</b>
					</ul>
				</div>
			</td>
			<td class=''>
				<b><?= sprintf("\$%u/mo or \$%u/yr (%u%% off)", ($monthly=$plans['dedicated']['amount']/100), ($yearly=$plans['dedicated_yearly']['amount']/100),
					($monthly-$yearly/12)/($monthly)*100); ?></b>
				<br/>
				<br/>
				<br/>
				<? if($plan == 'dedicated') {?>
					<i>Current Plan</i>
				<? } else { ?>
					<?= $this->Html->add("Select plan",array('rescuer'=>1,'action'=>'upgrade','dedicated')); ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td>
				<h4 class='bold'>Unlimited Account</h4>
				<div class='margintop10 marginbottom25 '>
					Everything with the dedicated plan, plus:
					<ul>
					<li>Unlimited adoptable listings
					<li>Unlimited volunteers and fosters with access to add updates to your rescue site and adoptable listings
					</ul>
				</div>
			</td>
			<td class=''>
				<b><?= sprintf("\$%u/mo or \$%u/yr (%u%% off)", ($monthly=$plans['unlimited']['amount']/100), ($yearly=$plans['unlimited_yearly']['amount']/100),
					($monthly-$yearly/12)/($monthly)*100); ?></b>
				<br/>
				<br/>
				<? if($plan == 'unlimited') {?>
					<i>Current Plan</i>
				<? } else { ?>
					<?= $this->Html->add("Select plan",array('rescuer'=>1,'action'=>'upgrade','unlimited')); ?>
				<? } ?>
			</td>
		</tr>
		</table>
	</div>
	<div class='col-md-4'>
		<h4>With every account, you can:</h4>
		<ul>
			<li>Find volunteers and fosters via our <?= $this->Html->link("Volunteer Directory", "http://{$default_domain}/volunteers",array('target'=>'_new')); ?>
			<li>Share recent news, photos and happy tails, list upcoming events and useful resources for prospective pet owners
			<li>Receive donations and sponsorships via built-in forms</li>
			<li>List your adoptables for FREE in our <?= $this->Html->link("Adoptables Directory", "http://{$default_domain}/adoptables",array('target'=>'_new')); ?>
			<li>List your rescue organization for FREE in our <?= $this->Html->link("Rescue Directory", "http://{$default_domain}/rescues",array('target'=>'_new')); ?>
		</ul>
	</div>
</div>
