<? $this->assign("page_title", "Custom-Built Web Design Projects"); ?>
<? $this->start("cta"); ?>
<div class='row'>
	<div class='col-md-6 center_align'>
		<?= $this->Html->link("View Portfolio", "/portfolio?cta=1", array('class'=>'btn btn-success')); ?><br/>
			to see some sample work
	</div>
	<div class='col-md-6 center_align'>
		<?= $this->Html->link("Contact Us", "/contact?cta=1", array('class'=>'btn btn-primary')); ?><br/>
			to discuss your project
	</div>
</div>
<div class='row margintop25'>
	<div class='col-md-6 center_align'>
		<?= $this->Html->link("View Demo Site", "http://demo.{$default_domain}/", array('class'=>'btn btn-danger')); ?><br/>
			to try out an example website. Just sign in with "demo@hopefulpress.com" and "demo1234" as the password.
	</div>
	<div class='col-md-6 center_align'>
		<?= $this->Html->link("View DIY Websites", "/features?cta=1", array('class'=>'btn btn-warning')); ?><br/>
			if you are budget-conscious and want to create a basic website yourself
	</div>
</div>
<? $this->end("cta"); ?>

<div class='index row'>
	<p class='medium bold'>
		All of our projects are based on the following standards:
	</p>
<div class='col-md-6'>
	<h4><?= $this->Html->fa("android green"); ?> Mobile-friendly</h4>
	<p>View your website accessible on devices of all shapes and sizes - smart phones, tablets, laptops, desktops. Take advantage of mobile technology to make collaboration and education available more easily.
</div>
<div class='col-md-6'>
	<h4><?= $this->Html->fa("facebook-official blue"); ?> Social media integration</h4>
	<p>Tools and icons integrate with social media networks, allowing  readers to share content easily with others and be notified of updates from your organization.
</div>
</div>
<div class='row'>
<div class='col-md-6'>
	<h4><?= $this->Html->g("edit green"); ?> Update your own content</h4>
	<p>We've built our own content management system so you can update your content at your convenience. Post news, share events and photos, list links, share downloads, add unlimited pages of informational content.
</div>
<div class='col-md-6'>
	<h4><?= $this->Html->fa("rocket red"); ?> Quick development</h4>
	<p>Our goal is to get your website up as soon as possible. Typical turnaround is 2 weeks, depending on the size of your project.
</div>
<div class='col-md-12'>

	<h4><?= $this->Html->fa("usd green"); ?> Affordable pricing</h4>
		
	<p class='alert alert-info'>Note: <?= $this->Html->link("Contact us", "/contact"); ?> to discuss pricing for custom web apps and web sites.

	<p>We keep our community websites affordable by offering the tools to help you help yourself.

	<p>Setup and design of basic community websites start at a <b>flat rate of $200</b>. 
	<p>This includes one custom design and unlimited pages, including the home page, about us page, contact us page, unlimited projects, links to resources, files for download, news, events and photos. You can create unlimited user accounts for others to contribute and manage their own content. We provide the web forms so you can fill in your basic content yourself.
	<p>Custom features beyond our core set may incur additional costs. If you're budget-conscious, we can always get a basic website operational and wait until later for additional features.
	<p>Hosting (maintenance) fees are <b>$15/mo</b>, including our administrative interface to add and update unlimited content at your convenience. 
</div>

</div>
<div>

	<!--
	<h3>Agile development</h3>
	<p>We can develop basic prototypes of your website/web app in weeks (depending upon availability of specifications). We can launch your site/service in stages to keep within your budget.
	<p>Our pricing model is based upon the size of the project and time spent rolling out new milestones. In order to best serve you so you benefit as rapidly as possible, we charge by the project (not by the hour). A basic custom project (about 3 weeks) can typically start at $500. Generally, we ask for at least a 25% deposit, depending on project size.
	-->

	<?= $this->fetch("cta"); ?>


	<h2 id='features'>Website Features</h2>
	<p class='medium bold'>
		We can bring any tool or feature to life. Some of our more common features and tools include:
	</p>
	<h3>Branded Portals and Private Member Areas</h3>
	<p>
		Within your web service, we can provide hosting multiple websites, including custom branding for each site. Along with publically available content, we can create private member areas that remain password-protected.
		
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/home1.png">
			<img src="/www/images/freelance/thumbs/home1.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/members.png">
			<img src="/www/images/freelance/thumbs/members.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>Blogs, News, Events, Pages</h3>
	<p>
		Publish news, events and other educational content to benefit your audience.
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/news_add.png">
			<img src="/www/images/freelance/thumbs/news_add.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/news.png">
			<img src="/www/images/freelance/thumbs/news.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/events.png">
			<img src="/www/images/freelance/thumbs/events.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/pages.png">
			<img src="/www/images/freelance/thumbs/pages.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>Multimedia</h3>
	<p>
		Share image galleries, videos and audio
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/photos.png">
			<img src="/www/images/freelance/thumbs/photos.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/videos.png">
			<img src="/www/images/freelance/thumbs/videos.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>Easy-to-use Content Administration and Collaboration</h3>
	<p>
		Update your own content in minutes, and let users pitch in by managing content on their own.
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/events_edit.png">
			<img src="/www/images/freelance/thumbs/events_edit.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/login.png">
			<img src="/www/images/freelance/thumbs/login.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>Website Metrics / Statistics </h3>
	<p>
		Measure how much your website is used and track your growth with custom metrics
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/stats.png">
			<img src="/www/images/freelance/thumbs/stats.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/stats2.png">
			<img src="/www/images/freelance/thumbs/stats2.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>User Profiles / Social Networks</h3>
	<p>
		Create user profiles for members and communicate among each other on a custom-built social network
	</p>
	<div>
		<a class='lightbox' href="/www/images/freelance/users.png">
			<img src="/www/images/freelance/thumbs/users.png"/>
		</a>
	</div>
	<div class='clear'></div>
	<h3>E-commerce</h3>
	<p>
		Shopping carts, plus process payment for product sales and billing for services/memberships
	</p>
	<div>
		<a class='lightbox' href="/www/images/hd/cart.png">
			<img src="/www/images/hd/thumbs/cart.png"/>
		</a>
		<a class='lightbox' href="/www/images/hd/checkout.png">
			<img src="/www/images/hd/thumbs/checkout.png"/>
		</a>
		<a class='lightbox' href="/www/images/freelance/billing.png">
			<img src="/www/images/freelance/thumbs/billing.png"/>
		</a>
	</div>
	<div class='clear'></div>
</div>
<style>
.lightbox
{
	border: solid 1px #999;
	margin: 5px;
	display: block;
	float: left;
}
</style>

<hr/>

<?= $this->fetch("cta"); ?>
