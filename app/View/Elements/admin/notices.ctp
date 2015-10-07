<? if(!empty($adminNotices) && !$this->fetch("admin_notices_disabled")) { ?>
<? foreach($adminNotices as $notice) { ?>
<div id='notices'>
	<?= $this->element("admin/notice/$notice"); ?>
</div>
<? } ?>
<? } ?>
