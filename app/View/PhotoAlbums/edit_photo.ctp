<? if(empty($ix)) { $ix = 0; } ?>
		<div id='Photo_<?=$photo['id'] ?>' class='left margin15 Photo item width160'>
			<?= $this->Form->hidden("Photo.$ix.id",array('value'=>$photo['id'])); ?>
			<div class='controls'>
				<div class='left'>
				<?= $this->Html->blink('arrow-left', "", array('controller'=>'photos','action'=>'rotate', $photo['id'], -1), array('class'=>'json btn-primary btn-xs','title'=>'Rotate left','e'=>0)); ?>
				&nbsp;
				&nbsp;
				<?= $this->Html->blink('arrow-right', "", array('controller'=>'photos','action'=>'rotate', $photo['id'], 1), array('class'=>'json btn-primary btn-xs','title'=>'Rotate right','e'=>0)); ?>
				</div>
				<div class='right'>
					<?= $this->Html->blink("delete", '', array('controller'=>'photos','action'=>'delete',$photo['id'],'ext'=>'json'), array('id'=>'PhotoDelete'.$photo['id'],'class'=>'json btn-xs btn-danger', 'confirm'=>'Are you sure you want to remove this photo?','title'=>'Delete this photo','e'=>0)); ?>
				</div>
				<div class='clear'></div>
			</div>
			<div class='height100'>
				<?= $this->Html->image("/photos/thumb/".$photo['id'],array('class'=>"Photo thumb")); ?>
			</div>

			<div class='sortable_hide height100'>
				<?= $this->Form->input("Photo.$ix.caption", array('type'=>'textarea','rows'=>5,'cols'=>false,'class'=>'width150','value'=>$photo['caption'],'placeholder'=>'Say something about this picture...','label'=>false)); ?>
			</div>
		</div>

