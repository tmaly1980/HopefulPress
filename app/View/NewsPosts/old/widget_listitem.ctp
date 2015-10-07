<div class="paddingtop5">
	<? if(!empty($newsPost['NewsPost']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
		<?= $this->element("../PagePhotos/thumb", array('id'=>$newsPost["NewsPost"]['page_photo_id'],'href'=>array('plugin'=>null,'controller'=>'news_posts','action'=>'view',$newsPost['NewsPost']['idurl']))); ?>
	<? } ?>
	<div class="left width150 marginright15 small">
		<?= $this->Date->mondy($newsPost['NewsPost']['created']); ?>
	</div>
	<div class="wrap">
		<?= $this->Html->link($newsPost['NewsPost']['title'], array('controller'=>'news_posts','action'=>'view',$newsPost['NewsPost']['idurl']), array('class'=>'title')); ?>
		<p class="small">
			<?= !empty($newsPost['NewsPost']['summary']) ? $newsPost['NewsPost']['summary'] : $this->Text->truncate(strip_tags($newsPost['NewsPost']['content'])); ?>
		</p>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>

