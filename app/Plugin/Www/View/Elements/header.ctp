<? $this->Html->og_image(Router::url("/rescue/images/header-bg.png",true)); ?>
<? $hostname = HostInfo::hostname(); ?>
<? $default_domain = HostInfo::default_domain(); ?>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand nobr" href="/">
	  	<img src='/www/images/paw-logo-small.png'/>
	  	Hopeful Press
		<? if($hostname == 'blog') { ?>
		Blog
		<? } ?>
  	  </a>
        </div>
        <div class="collapse navbar-collapse">
		<!--
		<p class='navbar-text navbar-right'>
        		<?#= $this->Html->link($this->Html->image("/core/images/icons/facebook.png"), "http://www.facebook.com/HopefulPress",array('title'=>'Find us on Facebook')); ?>
		</p>
		-->
          	<ul class="nav navbar-nav navbar-right">
		<? if($hostname == 'blog') { ?>
          		<li>
				<div class='padding5'>
				<a class='white btn btn-success navbar-btn' href="http://<?= $default_domain ?>/">Websites for Animal Rescues</a>
				</div>
			</li>
		<? } else { ?>
			<!--
          		<li><a href="/">Home</a></li>
			-->
          		<li><a href="/pages/features">Features</a></li>
          		<li><a class='red bold' href="/pages/demo" target="_new">Example Website</a></li>
          		<li><a class='' href="/pages/customers" target="">Existing Customers</a></li>
			<!--
          		<li><a href="/pages/faq">FAQ</a></li>
          		<li><a href="/pages/designs">Designs</a></li>
			-->
          		<li><a href="/pages/signup">Pricing &amp; Signup</a></li>
          		<li><a href="/pages/about">About Us</a></li>
          		<li><a href="/consult">Contact Us</a></li>
			<!--
          		<li>
				<div class='padding5'>
				<a class='btn btn-danger' target='_new' href="http://blog.<?= $default_domain ?>/?HOPEFULPRESS=<?=session_id()?>">Blog</a></li>
				</div>
			</li>
			-->
			<!--
          		<li><a class='bold orange' target='_new' href="http://support.<?= $default_domain ?>/"><i class='fa fa-life-ring'></i> Support</a></li>
			-->
		<? } ?>
          	</ul>
      </div><!--/.nav-collapse -->
      </div>
    </div>
<? if(!empty($this->request->params['manager'])) { ?>
<div class='padding5 navbar'>
<ul class='nav navbar-nav'>
	<li> <?= $this->Html->link("Manager", "/manager"); ?>
	<li> <?= $this->Html->link("Marketing Homepage", "/manager/tracker/tracker/page_view/Marketing?href=/"); ?>
	<li> <?= $this->Html->link("Custom Consultations", "/manager/www/intake_surveys"); ?>
	<li> <?= $this->Html->link("Contact Requests", "/manager/www/contact_requests"); ?>
</ul>
</div>
<? } ?>
