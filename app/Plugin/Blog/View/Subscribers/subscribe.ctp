<b>We're currently looking for beta testers for our DIY web hosting service for grassroots communities.</b>
<br/>We want to make it possible for everyday people to organize online and make a positive impact in their communities. 
<br/>No setup fees, no obligations, and just $15/mo after the 30-day free trial if you continue.
	<!--Get exclusive beta access to our Hopeful Press website service, plus
	get notified of new blog posts, product updates, or special offers
	-->
<?= $this->Form->create("Subscriber", array('id'=>'SubscriberForm','url'=>"/blog/subscribers/subscribe",'class'=>'ajax','data-update'=>'subscribe')); ?>
	<?= $this->Form->input("email", array('label'=>false,'placeholder'=>'Your email')); ?>
	<?= $this->Form->input("name", array('label'=>false,'placeholder'=>'Your name (optional)')); ?>
<?= $this->Form->end('Subscribe'); ?>
