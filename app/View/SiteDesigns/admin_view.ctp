<? $this->assign("admin_notices_disabled",true); ?>
<!-- gotta set 'rescue.rescue' hardcoded... -->
<? $this->start("prepend_main_wrapper");  ?>
<div style="border-bottom: solid 5px #CCC;" class='whitebg padding10 paddingleft25'>
	<?= $this->Session->flash(); # Clears so not displayed below ?>
	<?= $this->Html->css("admin/designer"); ?>
	<?= $this->element("../SiteDesigns/admin_edit"); ?>
	<h3>Preview:</h3>
</div>
<? $this->end("");  ?>
<!--
just load totally with ajax... which should render up until #main
THEN we need a layout that is empty inside #main
-->
<script>
//$('#main').load("/admin/homepages/view?preview=1");
</script>

<?= $this->requestAction("/admin/homepages/preview",array('return')); ?>
