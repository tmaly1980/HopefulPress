<? $pid = Configure::read("project_id"); ?>
<?= $this->element("Sortable.js"); ?>

<? $this->assign("page_title", "Pages"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<? if($this->Html->is_admin() || !empty($topics) || !empty($other_pages)) { # Don't let contributors add pages until topics exist ?>
		<?= $this->Html->add("Add Page", array("user"=>1,"action"=>"add")); ?>
	<? } ?>
	<? if(count($topics) + count($other_pages) > 1) { ?>
	<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'sorter')); ?>
	<? } ?>
<? } ?>
<? $this->end(); ?>
<div class="pages index row">
	<!-- XXX TODO VIEW AS TREE , optional resort, - icon toggler -->
	<!-- TODO sorting with tree -->
	<!-- IMPLEMENT ARROW/CLICK TO EXPAND -->

	<div class='col-md-6'>
	<h3>Topics</h3>
	<? if(empty($topics)) { ?>
		<div class='nodata'>No topics</div>
	<? } else { ?>
		<?= $this->element("../Pages/list", array('pages'=>$topics,'id'=>'pages_')); ?>
	<? } ?>
	</div>
<? if(empty($pid)) { ?>
	<div class='col-md-6'>
	<h3>Other Pages</h3>
	<? if(empty($other_pages)) { ?>
		<div class='nodata'>No other pages</div>
	<? } else { ?>
		<?= $this->element("../Pages/list", array('pages'=>$other_pages,'id'=>'pages_0')); ?>
	<? } ?>
	</div>
<? } ?>

</div><!-- end containing of content -->

<script>
$('#sorter').sorter('.pagelist',{
	prefix: "user",
	axis: 'both',
});//['#topics','#other']);
// XXX TODO makes sense to be able to shuffle from topics to other pages and back...
// ie ALSO alter parent_id (null=topic, 0=other)
// DONE via 'sort' call on each.

</script>
