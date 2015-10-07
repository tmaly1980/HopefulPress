<div style="padding-top: 5px;">
	<? if(!empty($newsPost['NewsPost']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
		<?= $this->element("../PagePhotos/thumb_newsletter", array('id'=>$newsPost["NewsPost"]['page_photo_id'])); ?>
	<? } ?>
	<div class='small' style='float: left; width: 150px; margin-right: 15px;'>
		<?= $this->Date->mondy($newsPost['NewsPost']['created']); ?>
	</div>
	<div style='overflow: hidden;'>
		<?= $this->Html->link($newsPost['NewsPost']['title'], array('controller'=>'news_posts','action'=>'view',$newsPost['NewsPost']['idurl']), array('class'=>'title')); ?>
		<p class='small'>
			<?= !empty($newsPost['NewsPost']['summary']) ? $newsPost['NewsPost']['summary'] : $this->Text->truncate(strip_tags($newsPost['NewsPost']['content'])); ?>
		</p>
	</div>
	<div style='clear: both;'></div>
</div>

