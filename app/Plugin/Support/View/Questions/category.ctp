<? $this->assign("page_title", (!empty($category) ? $category['Category']['title']:"Uncategorized"). " - Questions"); ?>
<? $this->start("title_controls"); ?>
<? if($this->User->me()) { ?>
	<?= $this->Html->add("Ask a question",array('user'=>1,'action'=>'add')
<? } ?>
<? $this->end(); ?>

<? $this->start("rightcol"); ?>
<? if($categories = $this->requestAction("/support/questions/categories",array('return'))) { ?>
	<h4>Categories</h3>
	<?= $categories ?>
<? } ?>
<? $this->end(); ?>

<div class='index'>
<? if(!empty($questions)) { ?>
	<?= $this->element("../Questions/list", array('questions'=>$questions)); ?>
<? } ?>

</div>
