<? $this->assign("page_title", "Questions"); ?>
<? $this->start("title_controls"); ?>
<? if($this->Html->me()) { ?>
	<?= $this->Html->add("Ask a question",array('user'=>1,'action'=>'add')); ?>
<? } ?>
<? $this->end(); ?>

<? $this->start("rightcol"); ?>
<? if(!empty($categories)) { ?>
	<h4>Categories</h3>
	<? foreach($categories as $cat) { ?>
	<p> <?= $this->Html->link($cat['Category']['title'], array('action'=>'category',$cat['Category']['id'])); ?> (<?= $uncategorized_count ?>) </p>
	<? } ?>
	<? if(!empty($uncategorized_count)) { ?>
	<p> <?= $this->Html->link("Uncategorized", array('action'=>'category')); ?> (<?= $uncategorized_count ?>) </p>
	<? } ?>
<? } ?>
<? $this->end(); ?>

<div class='index'>
<? if(!$this->Html->me()) { ?>
	<?= $this->Html->blink("user", "Sign in", "/user/users/login",array('class'=>'btn-success')); ?> to ask a question. Only registered users can ask a question. If you are not a registered user, please <?= $this->Html->link("contact us via our website", "http://www.{$default_domain}/#contact"); ?>
<? } ?>
<? if(!empty($questions)) { ?>
	<h3>New Questions</h3>
	<?= $this->element("../Questions/list", array('questions'=>$questions)); ?>
<? } ?>

<? if(!empty($previous_questions)) { ?>
<h3>Previous Questions</h3>
	<?= $this->element("../Questions/list", array('questions'=>$previous_questions)); ?>

<?= $this->element("pager"); ?>
<? } ?>
</div>
