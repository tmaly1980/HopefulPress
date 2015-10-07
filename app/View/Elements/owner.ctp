<? if($this->Form->id()) { # New record, dont ask, assume self via Autouser ?>
	<?= $this->Form->input("user_id",array('label'=>'Owner','default'=>$this->Form->me(),'empty'=>'- No owner -')); ?>
<? } ?>
