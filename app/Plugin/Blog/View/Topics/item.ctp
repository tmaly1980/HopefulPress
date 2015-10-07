<?
	$url = array('action'=>'view',$topic['Topic']['idurl']); 
	$tid = $topic['Topic']['id'];
	
	?>
<div class='margintop10 padding10 minheight200'>
	<div class='large marginbottom5'> <?= $this->Html->link($topic['Topic']['title'], $url, array('class'=>'color')); ?> 
		<? if(!empty($topic['Topic']['draft'])) { ?>
		<b> - Draft</b>
		<? } ?>
	</div>
	<!--
	<div class='italic'><?= $this->Date->mondy($topic['Topic']['created']); ?></div>
	-->

	<div class='paddingtop15 double'>
		<?= $topic['Topic']['content']; ?>
	</div>

	<?= $this->element("../Topics/posts",array('posts'=>$topic['Posts'])); ?>

	<? if(!empty($in_manager)) { ?>
	<div class='margintop50'>
		<?= $this->Html->link("<span>Add post</span>", array('controller'=>'posts','action'=>'add','blog_topic_id'=>$topic['Topic']['id']), array('class'=>'add_button')); ?>
	</div>
	<? } ?>

	<? if(!empty($prod)) { ?>
	<hr/>
	<div id="Comments">
	<? if(!empty($this->Facebook)) { # && Configure::read("prod")) { ?>
		<?= $this->Facebook->comments(array('href'=>Router::url($this->here,true))); ?>
	<? } ?>
	</div>
	<? } ?>

</div>
