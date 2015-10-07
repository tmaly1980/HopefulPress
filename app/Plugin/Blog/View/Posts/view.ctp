<?= $this->element("Www.sidebar"); #  Load into rightcol ?>
<? $this->assign("og_image_single",true); # If we have one, dont mix up others; Might want to put inside PagePhotos.view... ?>
<? $this->set("share", true); ?>
<? $id = $post['Post']['id']; ?>
<? $this->assign("page_title", $post['Post']['title'].(!empty($post['Post']['draft']) ? " - Draft" : "")); ?>

<div class="posts view">
	<div align=''><?= $this->Time->mondy($post['Post']['created']); ?></div>

	<?= $this->element("PagePhotos.view",array('align'=>'block','width'=>'600')); ?>

	<div id='BlogContent' class='maxwidth600 double margintop25'>
		<?= $post['Post']['content'] ?>
	</div>
	<style>
	#BlogContent p:first-child
	{
		font-weight: bold;
		margin-bottom: 25px;
	}
	</style>

	<!--

	- related tags
	- related articles (based on tags) - ?sidebar?
	- sidebar timeline + tag cloud + web hosting ad

	facebook (or anonymous?) comments
		- so posted on fb

	-->

	<? /* if(Configure::read("prod") && !empty($this->Facebook)) { ?>
		<?= $this->Facebook->like($this->Html->url($this->here,true)); ?>
	<? } */ ?>

	<?#=$this->HpComments->comments(array('modelClass'=>'BlogPost',$id)); ?>

	<hr/>
	<?= $this->element("../Posts/taglist"); ?>

	<hr/>
	<div id="Comments">
	<? if(!empty($this->Facebook)) { # && Configure::read("prod")) { ?>
		<?= $this->Facebook->comments(array('href'=>Router::url($this->here,true))); ?>
	<? } ?>
	</div>
</div>
