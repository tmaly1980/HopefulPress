<? if($this->Paginator->params(!empty($model)?$model:null)) { ?>
	<? if(!empty($counter)) { ?>
	<div class=''>
		<?= $this->Paginator->counter(array('format'=>"{:count} total, page {:page} of {:pages}")); ?>
	</div>
	<? } ?>
<ul class="pagination pagination-sm">
<?php
	if($this->Paginator->hasPrev()) { 
		echo $this->Paginator->prev('&larr; Previous', array('class' => 'prev','tag' => 'li','escape' => false), '<a onclick="return false;">&larr; Previous</a>', array('class' => 'prev disabled','tag' => 'li','escape' => false));
	}
	echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentClass' => 'active','currentTag' => 'a'));
	if($this->Paginator->hasNext()) {
		echo $this->Paginator->next('Next &rarr;', array('class' => 'next','tag' => 'li','escape' => false), '<a onclick="return false;">Next &rarr;</a>', array('class' => 'next disabled','tag' => 'li','escape' => false));
	}
?>
</ul>
<? } ?>
<div class='clear'></div>
