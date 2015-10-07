<?= $this->element("Sortable.js"); ?>

<? $pid = Configure::read("project_id"); ?>
<? $this->assign("page_title", !empty($downloadPage['DownloadPage']['title']) ? $downloadPage['DownloadPage']['title'] : 'Downloads'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->add("Add Download", array("user"=>1,"controller"=>'downloads',"action"=>"add")); ?>
	<? /* if(count($downloads) > 1) { # XXX somehow count for downloads within categories ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'download_sorter')); ?>
	<? } */ ?>
<? } ?>
<? $this->end(); ?>



<div class="pages form">

	<div id="DownloadPage_Introduction">
	<? if(!empty($downloadPage['DownloadPage']['introduction'])) { ?>
		<?= nl2br($downloadPage['DownloadPage']['introduction']); ?>
	<? } ?>
	</div>

	<? if($this->Html->can_edit()) { ?>
	<script>
	$('#LayoutTitle').inline_edit({prefix: 'admin', link: '', inline:true, model: 'DownloadPage',field:'title',class: 'margintop10',append: "<?= !empty($pid)?"project_id:$pid":"" ?>"});
	$('#DownloadPage_Introduction').inline_edit({prefix: 'admin', link: 'Add introduction/Edit introduction',append: "<?= !empty($pid)?"project_id:$pid":"" ?>"});
	</script>
	<? } ?>
	<div class='clear'></div>

	<div id="Downloads" class='margintop25'>
		<?= $this->element("../Downloads/list"); ?>
	</div>

	<script>
	<? /* if(count($downloads) > 1) { ?>
	$('#download_sorter').sortEnable('.downloadlist',{axis: 'y',controller: 'downloads'});
	<? } */ ?>

	</script>

</div>
