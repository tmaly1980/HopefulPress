<? $this->assign("page_title", "About ".$rescue['Rescue']['title']); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? if($this->Rescue->admin()) { ?>
<? $this->start("title_controls"); ?>
	<?= $this->Html->edit("Update",array('admin'=>1,'controller'=>'rescues','action'=>'edit','#'=>'about')); ?>
<? $this->end("title_controls"); ?>
<? } ?>

<?= $this->element("Sortable.js"); ?>

<div class="pages form">

	<div class='minheight100'>
			<?= $this->element("PagePhotos.view",array('photoModel'=>'AboutPhoto')); ?>
	<? if(empty($rescue['Rescue']['about']) && empty($rescue['Rescue']['history']) && $this->Rescue->admin()) { ?>
		<div class='alert alert-info'>
			You haven't filled out any information about your organization yet.
			<?= $this->Html->edit("Add details", array('rescuer'=>1,'action'=>'edit','#'=>'about'),array('short'=>false)); ?>
		</div>
	<? } else { ?>
			<? if(!empty($rescue['Rescue']['about'])) { ?>
			<p class='bold doublespacing minheight50'>
				<?= $rescue['Rescue']['about'] ?>
			</p>
			<? } ?>

			<? if(!empty($rescue['Rescue']['mission'])) { ?>
			<h3 class='marginbottom0'>Our Mission</h3>
			<p class='marginleft15 doublespacing minheight50'>
				<?= $rescue['Rescue']['mission'] ?>
			</p>
			<? } ?>

			<? if(!empty($rescue['Rescue']['history'])) { ?>
			<h3 class='marginbottom0'>History</h3>
			<p class='marginleft15 doublespacing minheight50'>
				<?= $rescue['Rescue']['history'] ?>
			</p>
			<? } ?>
	<? } ?>
	</div>

	<div class='clear'></div>

	<hr/>

	<? if(!empty($aboutPageBios) && $this->Rescue->admin()) { ?>
		<div class='right'>
		<?= $this->Html->add("Add Staff Bio", array('admin'=>1,'controller'=>'about_page_bios','action'=>'add')); ?>
		<? if(count($aboutPageBios) > 1) { ?>
		<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'bio_sorter')); ?>
		<? } ?>
		</div>
	<? } ?>
	<? if(!empty($aboutPageBios) || $this->Rescue->admin())  { ?>
	<h3 id='Rescue_BioTitle'><?= !empty($rescue['Rescue']['bio_title']) ? $rescue['Rescue']['bio_title'] : "Our Staff" ?></h3>
	<div class='clear'></div>
	<? } ?>

	<? if(empty($aboutPageBios) && $this->Rescue->admin()) { ?>
		<?= $this->Html->add("Add Staff Bios", array('admin'=>1,'controller'=>'about_page_bios','action'=>'add'),array('short'=>false)); ?>
		<div class='alert alert-info'>
			You can add biographies of individuals within your organization.
		</div>
	<? } ?>

<? if($this->Rescue->admin()) { ?>
	<script>
	//$('#Rescue_BioTitle').inline_edit({link: '', inline:'after'});
	</script>
<? } ?>
<div class='clear'></div>

	<div id="AboutPageBios">
		<?= $this->element("../AboutPageBios/list"); ?>
	</div>

<? if($this->Rescue->admin() && count($aboutPageBios) > 1) { ?>
	<script>
	$('#bio_sorter').sorter('.biolist',{axis: 'y',controller: 'about_page_bios'});
	</script>
<? } ?>

</div>
