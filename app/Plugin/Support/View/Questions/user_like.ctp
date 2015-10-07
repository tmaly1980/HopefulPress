<? 
$liked = Set::extract("/Like[user_id=$me]", $question);
?>
<div class='margintop10'>
	<? if($this->Html->me()) { ?>
	<?= $this->Html->blink("thumbs-up", $liked?"You Like This":"Like", array('user'=>1,'controller'=>'questions','action'=>'like',$question['Question']['id']),array('data-update'=>"question_likes_".$question['Question']['id'],'class'=>"ajax ".($liked?'btn-default':'btn-primary'))); ?>
	<? } ?>
	<div>
	<? if($likes = count($question['Like'])) { ?>
		<?= $likes > 1 ? "$likes likes" : "$likes like"; ?>
	<? } ?>
	</div>

</div>
