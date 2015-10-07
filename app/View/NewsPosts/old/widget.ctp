<? $pid = $this->Admin->project('id'); ?>
<div class="newsPosts widget">
	<h3>
		<?#= $this->Html->add_link(null,'news_posts'); ?>
		Recent News</h3>
	<? if(empty($newsPosts)) { ?>
		<div class='nodata'>
			There are no news posts yet.
			<? if(!empty($in_admin) && $this->Admin->access()) { ?>
				<?= $this->Html->link("Add a news post", array('controller'=>'news_posts','action'=>'add','project_id'=>$pid), array('class'=>'color green')); ?>
			<? } ?>
		</div>

	<? } else { ?>
<div class='items'>

		<? foreach($newsPosts as $newsPost) { ?>
			<?= $this->element("../NewsPosts/widget_item", array('newsPost'=>$newsPost)); ?>
		<? } ?>
</div>
		
		<div align='right' class='padding10'>
			<?= $this->Html->link("More news...", array('controller'=>'news_posts','action'=>'index','project_id'=>$pid), array('class'=>'small more')); ?>
		</div>

	<? } ?>
</div>
