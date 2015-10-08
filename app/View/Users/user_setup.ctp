<? $this->assign("search_disabled",true); ?>
<div class='alert alert-info'>
	Please provide some details about yourself before continuing
</div>
<?= $this->element("../Users/user_account",array('setup'=>1)); ?>

