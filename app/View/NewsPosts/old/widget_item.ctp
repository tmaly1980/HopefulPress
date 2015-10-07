	<div class="paddingtop15">
		<div>
			<?= $this->Publishable->link($newsPost,'NewsPost'); ?>
		</div>
		<div class="XXleft">
			<?#= $this->element("widget_date", array('date'=>$newsPost['NewsPost']['created'])); ?>
			<?= $this->Date->mondy($newsPost['NewsPost']['created']); ?>
		</div>
		<div class="wrap">
			<? if(!empty($newsPost['NewsPost']['page_photo_id']) && $this->Admin->design("update_photos")) { ?>
				<?= $this->element("../PagePhotos/thumb", array('id'=>$newsPost["NewsPost"]['page_photo_id'],'wh'=>'225x150','href'=>array('plugin'=>null,'controller'=>'news_posts','action'=>'view',$newsPost['NewsPost']['idurl']))); ?>
			<? } ?>
			<div class="small">
				<?= $this->WPHtml->summary($newsPost['NewsPost']['content']); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>

