<? $this->assign("page_title", "Existing Customers"); ?>
<div class=''>
	<div class='row'>
		<div class='col-md-4'>
			<?= $this->Html->link($this->Html->image("/www/images/customers/htdr.png",array('class'=>'maxwidth100p border')), "http://happytailsdr.org/",array('class'=>'block padding10')); ?>
		</div>
		<div class='col-md-6'>
			<h3><?= $this->Html->link("Happy Tails Dachshund Rescue, Inc", "http://happytailsdr.org/", array('class'=>'')); ?></h3>
			<p>
			Our Mission at Happy Tails Dachshund Rescue, Inc. (HTDR) is to save as many dachshunds as possible from suffering and premature death due to abuse, neglect, abandonment, injury, or illness. We foster unwanted dogs whose owners are no longer able or willing to provide for their care and place them with adoptive families who can provide loving, forever homes. We spay/neuter all dogs before adoption, and contact our adoptive families in the first week to check on the health of their relationship with their new pet. Our area of operation is the East Valley of the Greater Phoenix Area, Casa Grande, and north Tucson.
			</p>
		</div>
	</div>

	<?= $this->element("Www.cta_footer"); ?>


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
