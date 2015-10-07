<? 
$liked = Set::extract("/Like[user_id=$me]", $message);
?>
<div class='margintop10'>
	<? if($this->Html->me()) { ?>
	<?= $this->Html->blink("thumbs-up", $liked?"You Like This":"Like", array('user'=>1,'controller'=>'messages','action'=>'like',$message['id']),array('data-update'=>"message_likes_".$message['id'],'class'=>"ajax btn-xs ".($liked?'btn-default':'btn-primary'))); ?>
	<? } ?>
	<div>
	<? if($likes = count($message['Like'])) { ?>
		<?= $likes > 1 ? "$likes likes" : "$likes like"; ?>
	<? } ?>
	</div>

</div>
