<? if(empty($updates['newsPosts']) && !$this->Html->me() /*&& !$this->Html->can_edit()*/) { return; } ?>

<? $prefix = Configure::read("members_only") ? "members" : "user"; ?>
<? $pid = Configure::read("project_id"); ?>
<? $project = Configure::read("project"); ?>

<div class='widget'>
<h3>
	<?= $this->Html->link("Recent News", array('controller'=>'news_posts','action'=>'index')); ?>
</h3>
<? if(false && $this->Html->can_edit()) { ?>
<div class='alert-warning dashed border2'>
	<?= $this->Html->add(!empty($updates['newsPosts']) ? "Add another news post":"Add your first news post", array($prefix=>1,'plugin'=>null,'controller'=>'news_posts','action'=>'add','project_id'=>$pid),array('class'=>'controls btn-warning','title'=>'Add News')); ?>
</div>
<? } ?>
<? if(empty($updates['newsPosts'])) { ?>
	<? if($this->Html->me()) { ?>
	<div class='dashed alert alert-info'>
		You have no news posts yet.
		<?= $this->Html->add("Add a news post", array('rescuer'=>1,'controller'=>'news_posts','action'=>'add')); ?>
	</div>
	<? } ?>
<? } else { ?>
	<div class=''>
	<? foreach($updates['newsPosts'] as $news) { 
		$intro = $this->Html->summary($news['NewsPost']['content']);
	?>
	<div class='item <?= !empty($wide) ? "wide":""?>'>
		<? if(!empty($news['NewsPost']['page_photo_id'])) { ?>
			<?= $this->element("PagePhotos.thumb", array('id'=>$news['NewsPost']['page_photo_id'],'href'=>array('plugin'=>null,'controller'=>"news_posts", "action"=>"view", $news['NewsPost']['idurl']),'class'=>"PagePhoto")); ?>
		<? } ?>
		<div class='item_details padding5'>
			<div> <?= $this->Time->mondy($news['NewsPost']['created']); ?> </div>
			<?= $this->Html->titlelink($news['NewsPost']['title'], array('plugin'=>null,'controller'=>'news_posts','action'=>"view", $news['NewsPost']['idurl'])); ?>

			<? if(!empty($wide) && !empty($intro)) { ?>
				<div><?= $intro ?>
					<?= !empty($wide) ? $this->Html->link("[read more...]", array('plugin'=>null,'controller'=>'news_posts','action'=>'view', $news['NewsPost']['idurl']), array('class'=>'underline bold')) : ""; ?>
				</div>
			<? } ?>
		</div>
	</div>
	<div class='clear'></div>
	<? } ?>
	<? if(!empty($updates['newsPosts'])) { ?>
	<div align='right'>
	<?= $this->Html->link("More news ".$this->Html->g("chevron-right"), array('plugin'=>null,'controller'=>"news_posts"), array('class'=>'btn more right_align medium bold')); ?>
	</div>
	<? } ?>
	</div>
<? } ?>
</div>
