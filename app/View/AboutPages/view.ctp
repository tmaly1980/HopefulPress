<? $this->assign("page_title", !empty($aboutPage['AboutPage']['title']) ? $aboutPage['AboutPage']['title'] : 'About Us'); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if($this->Html->can_edit()) { ?>
	<?= $this->Html->edit("Edit Page", array("admin"=>1,"action"=>"edit")); ?>
<? } ?>
<? $this->end(); ?>

<?= $this->element("Sortable.js"); ?>

<div class="pages form">

	<div class='minheight100'>
			<?= $this->element("PagePhotos.view"); ?>
	<? if(empty($aboutPage['AboutPage']['overview']) && empty($aboutPage['AboutPage']['mission']) && empty($aboutPage['AboutPage']['history']) && $this->Html->me()) { ?>
		<div class='alert alert-info'>
			You haven't filled out any information about your organization yet.
			<?= $this->Html->edit("Add details", array('admin'=>1,'action'=>'edit'),array('short'=>false)); ?>
		</div>
	<? } else { ?>
			<? if(!empty($aboutPage['AboutPage']['overview'])) { ?>
			<p class='bold doublespacing minheight50'>
				<?= $aboutPage['AboutPage']['overview'] ?>
			</p>
			<? } ?>

			<? if(!empty($aboutPage['AboutPage']['mission'])) { ?>
			<h3 class='marginbottom0'>Our Mission</h3>
			<p class='marginleft15 doublespacing minheight50'>
				<?= $aboutPage['AboutPage']['mission'] ?>
			</p>
			<? } ?>

			<? if(!empty($aboutPage['AboutPage']['history'])) { ?>
			<h3 class='marginbottom0'>History</h3>
			<p class='marginleft15 doublespacing minheight50'>
				<?= $aboutPage['AboutPage']['history'] ?>
			</p>
			<? } ?>
	<? } ?>
	</div>

	<div class='clear'></div>

	<hr/>

	<? if(!empty($aboutPageBios) && $this->Html->can_edit()) { ?>
		<div class='right'>
		<?= $this->Html->add("Add Staff Bio", array('admin'=>1,'controller'=>'about_page_bios','action'=>'add')); ?>
		<? if(count($aboutPageBios) > 1) { ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'bio_sorter')); ?>
		<? } ?>
		</div>
	<? } ?>
	<? if(!empty($aboutPageBios) || $this->Html->can_edit())  { ?>
	<h3 id='AboutPage_BioTitle'><?= !empty($aboutPage['AboutPage']['bio_title']) ? $aboutPage['AboutPage']['bio_title'] : "Our Staff" ?></h3>
	<div class='clear'></div>
	<? } ?>

	<? if(empty($aboutPageBios) && $this->Html->can_edit()) { ?>
		<?= $this->Html->add("Add Staff Bios", array('admin'=>1,'controller'=>'about_page_bios','action'=>'add'),array('short'=>false)); ?>
		<div class='alert alert-info'>
			You can add biographies of individuals within your organization.
		</div>
	<? } ?>

<? if($this->Html->can_edit()) { ?>
	<script>
	$('#AboutPage_BioTitle').inline_edit({link: '', inline:'after'});
	</script>
<? } ?>
<div class='clear'></div>

	<div id="AboutPageBios">
		<?= $this->element("../AboutPageBios/list"); ?>
	</div>

<? if($this->Html->can_edit() && count($aboutPageBios) > 1) { ?>
	<script>
	$('#bio_sorter').sorter('.biolist',{axis: 'y',controller: 'about_page_bios'});
	</script>
<? } ?>

</div>
