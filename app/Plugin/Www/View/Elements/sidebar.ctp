<? $this->start("rightcol"); ?>
<? if(Configure::read("blog")) { ?>
<div class=''>
	<?= $this->element("Www.about"); ?>
	<?= $this->Html->link($this->Html->image("/rescue/images/screenshot.png",array('class'=>'width100p border border5')),"http://$default_domain/"); ?>
	<p class='bold'>
	Hopeful Press specializes in low-cost professional websites for animal rescue groups. A high-quality website inspires people to donate, volunteer or adopt. Click to learn more.
	</p>
	<div class='center_align'>
	<?= $this->Html->link("Animal Rescue Websites", "http://$default_domain",array('class'=>'btn btn-success')); ?>
	</div>

<? } ?>
<? $this->end(); ?>
<? /* $rightcol = $this->get("rightcol"); ?>
<? if((Configure::read("blog") || in_array($this->request->controller, array('static'))) && $rightcol !== false) { ?>
<? $this->start("rightcol"); ?>

<? if(Configure::read("blog")) { ?>
	<?= $this->element("Www.about"); ?>
	<hr/>
	<h4><?= $this->Html->link("Community Website Hosting", "http://www.{$default_domain}"); ?></h4>
	<div  class='alert alert-info'>
		<p>
		We want to make it possible for everyday people to organize online and make a positive impact in their communities.
		Low setup costs, just $15/mo hosting &amp; maintenance.
		</p>
		<br/>
		<div class='center_align'>
		<?= $this->Html->link("Learn More ".$this->Html->g("triangle-right"), "http://www.{$default_domain}",array('class'=>'btn btn-primary')); ?>
		</div>
	</div>
	<hr/>
<? } ?>

	<?= $this->element("Blog.subscribe_container"); ?>

	<? if(!empty($post['Post']['id'])) { ?>
        	<?= $this->requestAction("/blog/posts/related/".$post['Post']['id'],array('return')); ?>
	<? } ?>

        <?= $this->requestAction("/blog/posts/recent",array('return')); ?>

<? $this->end("rightcol"); ?>
<? } */ ?>
