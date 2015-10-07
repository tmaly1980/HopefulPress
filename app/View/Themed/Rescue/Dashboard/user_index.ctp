<? $this->assign("page_title", "Set Up Your Website"); ?>
<? $this->layout = 'admin'; ?>
<div class='index'>
	<!--
	<div class='alert alert-info'>
		We recommend you complete the steps below to fill in the basic information for your website
	</div>
	-->
	<div class='row'>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->fa("phone"). "Complete contact info", "/contact", array('class'=>'')); ?>
			<p>
			</p>
		</div>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->fa("university")."Add organizational history", "/", array('class'=>'')); ?>
			<p>
			</p>
		</div>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->fa("paint-brush")."Customize your design", "/admin/site_designs/view", array('class'=>'')); ?>
			<p>
			</p>
		</div>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->fa("paw")."Add adoptables", "/adoption/adoptables", array('class'=>'')); ?>
			<p>
			</p>
		</div>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->g("gift")."Enable donations", "/donation", array('class'=>'')); ?>
			<p>
			</p>
		</div>
		<div class='col-md-3 item'>
			<?= $this->Html->link($this->Html->g("globe")."Register domain name", "/admin/sites/view", array('class'=>'')); ?>
			<p>
				Use your own <b>'YourRescueWebsite.org'</b> address instead of <b>'<?=Router::url("/", array('full_base'=>true));  ?>'</b>.
			</p>
		</div>
	</div>

	<hr/>

	<div class='row'>
		<?= $this->Html->blink("home", "Continue to home page", "/",  array('div'=>'col-md-6','class'=>'btn-success')); ?>
		<?= $this->Html->blink("remove", "Always go to home page", "/",  array('div'=>'col-md-6','class'=>'btn-info white')); ?>
	</div>

</div>
<style>
.index .item
{
	padding: 5px;
	margin: 10px;
	text-align: center;
}
.index .item .glyphicon,
.index .item .fa
{
	font-size: 48px;
	display: block;
}
.index .item a
{
	font-size: 1.2em;
	border: none;
}
.index .item a:hover
{
	text-decoration: none !important;
}
</style>
