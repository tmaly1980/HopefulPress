<? $this->start("title_controls"); ?>
<? if(!empty($freetrial)) { # Only difference. Which button  text works better???? ?>
	<?= $this->Html->link("Free Trial", "/signup/trial:1",array('class'=>'btn btn-success')); ?>
<? } else { ?>
	<b>30-Day Free Trial</b>
	&nbsp;
	<?= $this->Html->link("Sign Up Now", "/signup/trial:1",array('class'=>'btn btn-success')); ?>
<? } ?>
<? $this->end("title_controls"); ?>
<? $this->assign("page_title", "Website Features"); ?>
<div class='features col-md-9 center'>
	<div class='alert alert-warning medium'>
	Hopeful Press provides professional rescue websites for the average animal rescue organization. We've done all the hard work for you so you can focus on finding forever homes for the adoptables in your care. 
	</div>

	<div class='feature'>
		<h3>Full website ready-to-go in minutes</h3>
		<p>
			Hopeful Press specializes in animal rescue websites, so we've done all the hard work for you. Sign up takes just a few minutes. 
			Just choose from one of our pre-made designs and fill in the blanks. No coding/design experience required. Our websites include a mobile-friendly interface viewable from smart phones and tablets for maximum accessibility.
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'signup','caption'=>'Easy signup form')); ?>
			<?= $this->element("thumb", array('src'=>'homepage','caption'=>'Instant website')); ?>
			<?= $this->element("thumb", array('src'=>'design','caption'=>'Personalize your design')); ?>
			<?= $this->element("thumb", array('src'=>'mobile','caption'=>'Mobile-friendly')); ?>
		</div>
		<div class='clear'></div>
	</div>
	<div class='feature'>
		<h3>Update and customize web pages yourself</h3>
		<p>
			Updating your website pages are easy through our administrative system.
			Adding updates personalizes your website and keeps visitors up-to-date. 
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'login','caption'=>'Update your website just by signing in')); ?>
			<?= $this->element("thumb", array('src'=>'admin_nav','caption'=>'All pages are updated through the administrative menu')); ?>
			<?= $this->element("thumb", array('src'=>'edit_links','caption'=>'Links to add/edit forms appear on each page')); ?>
			<?= $this->element("thumb", array('src'=>'adoptable_edit','caption'=>'Just fill in the blanks to add or update web pages')); ?>

			<?= $this->element("thumb", array('src'=>'updates','caption'=>'Post news, events and photo updates')); ?>
			<?= $this->element("thumb", array('src'=>'contact','caption'=>'Add contact details')); ?>
			<?= $this->element("thumb", array('src'=>'about','caption'=>'Share your story and list your staff')); ?>
			<?= $this->element("thumb", array('src'=>'resources','caption'=>'List useful resources for potential pet owners')); ?>
		</div>
		<div class='clear'></div>
	</div>
	<div class='feature'>
		<h3>Online adoptable database</h3>
		<p>
			List your currently available adoptables, with photos, biography, and stats. Share success stories of recently adopted animals.
			Search for previous adoptables in a database via name, breed, owner or microchip.
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'adoptable','caption'=>'List available adoptables')); ?>
			<?= $this->element("thumb", array('src'=>'adoption_stories','caption'=>'Share success stories')); ?>
			<?= $this->element("thumb", array('src'=>'homepage_gallery','caption'=>'Home page gallery')); ?>
			<?= $this->element("thumb", array('src'=>'adoptable_database','caption'=>'Searchable database')); ?>
		</div>
		<div class='clear'></div>
	</div>
	<div class='feature'>
		<h3>Ready-to-go forms for adoption, volunteer and foster requests</h3>
		<p>
			Receiving online adoption, volunteer or foster requests is easy with ready-to-go forms. Once filled out, you'll automatically be notified
			via email with details. You can also browse your list of active volunteers and fosters with contact information as needed.
			Forms can be customized to ask questions specific to your specialized breed or species (such as dog or cat-specific questions).
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'adoption','caption'=>'Online adoption form')); ?>
			<?= $this->element("thumb", array('src'=>'volunteer','caption'=>'Online volunteer request')); ?>
			<?= $this->element("thumb", array('src'=>'foster','caption'=>'Online foster form')); ?>
			<?= $this->element("thumb", array('src'=>'volunteer_list','caption'=>'List active volunteers and fosters')); ?>
		</div>
		<div class='clear'></div>
	</div>
	<div class='feature'>
		<h3>Online donations/sponsorships and mailing list</h3>
		<p>
			Receive one-time or recurring (monthly) donations to make caring for your adoptables possible. Any adoptable can be sponsored as needed to pay for extraordinary costs such as medical care. Collect visitors' email addresses so you can send email notices and periodic newsletters to promote your rescue.
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'donation','caption'=>'Online donations')); ?>
			<?= $this->element("thumb", array('src'=>'sponsorship','caption'=>'High-needs sponsorships')); ?>
			<?= $this->element("thumb", array('src'=>'subscribe','caption'=>'Collect mailing list')); ?>
			<?= $this->element("thumb", array('src'=>'newsletter','caption'=>'Send emails and newsletters via MailChimp')); ?>
		</div>
		<div class='clear'></div>
	</div>
	<!--
	<div class='feature'>
		<h3>Professional website and email addresses</h3>
		<p>
			use your own domain or get a new one
			email services @ your organization
		</p>
		<div>
			<?= $this->element("thumb", array('src'=>'ready_signup','caption'=>'Easy signup form')); ?>
			<?= $this->element("thumb", array('src'=>'ready_home','caption'=>'Instant website')); ?>
			<?= $this->element("thumb", array('src'=>'ready_design','caption'=>'Personalize your design')); ?>
			<?= $this->element("thumb", array('src'=>'ready_edit','caption'=>'Customize content easily')); ?>
		</div>
		<div class='clear'></div>
	</div>
	-->

	<hr/>

	<h2>Like what you see?</h2>
	<div  class='row center_align'>
		<div class='col-md-4'>
			<?= $this->Html->link("Pricing", "/pages/pricing",array('class'=>'btn btn-primary btn-lg')); ?>
		</div>
		<div class='col-md-4 padding10'>
			<?= $this->Html->link("Live Demo", "/pages/demo",array('class'=>'red bold medium')); ?>
		</div>
		<div class='col-md-4'>
		<? if(!empty($freetrial)) { # Only difference. Which button  text works better???? ?>
			<?= $this->Html->link("Free Trial", "/signup",array('class'=>'btn btn-success btn-lg')); ?>
			<br/>
			<br/>

		<? } else { ?>
			<?= $this->Html->link("Sign Up Now", "/signup",array('class'=>'btn btn-success btn-lg')); ?>
			<br/>
			<b>30-Day Free Trial</b>
		<? } ?>
		</div>
	</div>

<?/*

	<hr/>
	<hr/>
	<hr/>

	<div class=''>
		<img src="/rescue/images/features/thumbs/design.png" class='right shadow'/>
		<h3>Choose from several professional designs</h3>
		<p class=''>
			Have a professional image without messing with templates or Cascading Style Sheets (CSS). We've hand picked some of the best designs for you.
			You'll be able to customize your header and colors to your liking. <a href='#designs'>Take a look at some of the designs we have available</a>
		</p>
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/mobile.png" class='left shadow'/>
		<h3>Reach a wider audience through a mobile-friendly design</h3>
		<p>
			As of 2015, more people have mobile devices than desktop computers. When told about your organization via word-of-mouth, they will likely look up your website before they get home. With a mobile-friendly design, visitors can browse your website legibly, view adoptables, subscribe to your newsletter, make donations and fill out adoption requests from the convenience of their smart phone or tablet. 
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/adoptable-list.png" class='right shadow'/>
		<h3>List current adoptables</h3>
		<p>
			Share listings of your adoptables to encourage adoption, including detailed information, biography, pictures and videos.
			Share success stories with status updates of successful adoptions.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/adoption.png" class='left shadow'/>
		<h3>Receive online adoption and foster applications</h3>
		<p>
			Simplify the adoption process by receiving applications completed online.
			Grow your foster network by receiving foster applications online.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/donation.png" class='right shadow'/>
		<h3>Receive online donations and sponsorships</h3>
		<p>
			Make it easy for visitors to support your organization financially though one-time or recurring donations.
			Accept credit cards securely, with a low processing fee.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/share.png" class='left shadow'/>
		<h3>
		Encourage word of mouth through social sharing
		</h3>
		<p>
			Improve the chances of finding homes for adoptables by giving visitors the tools to share on their social networks.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/educate.png" class='right shadow'/>
		<h3>Educate readers about adoption, fostering and pet ownership</h3>
		<p>
			Educate potential pet owners with your adoption and foster policies, as well as other how-to articles pertaining to being a better pet owner.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/newsletter.png" class='left shadow'/>
		<h3>Send periodic email newsletters</h3>
		<p>
			Foster long-term relationships with supporters by sending email newsletters with updates of your adoptables, including news and events.
	</div>
	<div class='clear'></div>
	<div class=''>
		<img src="/rescue/images/features/thumbs/updates.png" class='right shadow'/>
		<h3>Share news, events, photos and videos</h3>
		<p>
		Keep your content updated regularly to encourage visitors to come back often and to share content with others. 
	</div>
	<div class='clear'></div>
*/?>
</div>
<style>
.feature a.lightbox:hover
{
	cursor: zoom-in;
}
.feature a.lightbox
{
	width: 200px;
	display: block;
	float: left;
	margin: 10px;
	text-align: center;
	min-height: 200px;
}
.feature a.lightbox img
{
	width: 100%;
	border: solid 1px #999;
}
/*
.features img
{
	display: block;
	max-width: 100%;
	height: auto;
}
.features h3
{
}
@media (min-width: 768px)
{
	.features img
	{
		margin: 20px;
	}
	.features p
	{
		line-height: 200%;
	}
}
@media (min-width: 998px)
{
	.features h3
	{
		padding-top: 100px;
	}
	.features img
	{
		margin-left: 50px;
		margin-right: 50px;
	}
}
.features > div
{
	margin-top: 25px;
}
*/
</style>
