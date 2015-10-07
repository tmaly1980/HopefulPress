<? $this->assign("page_title", "Members Only Area"); ?>
<? if(empty($memberPage)) { ?>
<? $this->layout  = 'admin'; ?>
<div>
	The members only area provides a password-protected area for website users to add and share private content.

	<?= $this->Html->add("Enable Members Area", array('admin'=>1,'action'=>'enable')); ?>
</div>

<? } else { ?>

<? $this->start("title_controls"); ?>
<? if($this->Html->is_admin()) { ?>
	<?= $this->Html->edit("Edit Page", array('admin'=>1,'action'=>'edit')); # Has picture, so can't really be done inline... ?>
	<?= $this->Html->delete("Disable", array('admin'=>1,'action'=>'disable')); ?>
<? } ?>
<? $this->end("title_controls"); ?>


<div class="view fontify <?#= $this->Admin->fontsize('default'); ?>">

	<div class='col-md-9'>
	
		<?= $this->element("PagePhotos.view",array('wh'=>'200x200')); ?>
	
		<div class="padding10 minheight100">
			<?= ($memberPage['MemberPage']['description']); ?>
		</div>
	</div>
	<div class='col-md-9'>
	
		<div class='minheight200'>
			<? if($this->Html->can_edit()) { ?>
			<div class='right'>
				<?= $this->Html->madd("Add Page", array('plugin'=>null,'controller'=>'pages','action'=>'add')); ?>
				<? if(count($updates['pages']) > 1) { # && $in_admin && $this->Admin->access()) { ?>
					<?= $this->Html->blink("sort", "Resort", "javascript:void(0)",array('id'=>'Pages_sorter')); ?>
				<? } ?>
			</div>
			<? } ?>
			<h3 class=''>
				More information
			</h3>
			<div class='clear'></div>
			<div class='margintop10'>
				<? if(!empty($updates['pages'])) { ?>
				<div id="Pages">
					<? foreach($updates['pages'] as $page) { ?>
					<div id="Page_<?= $page['Page']['id'] ?>" class="Page">
						<?= $this->Html->titlelink($page['Page']['title'], array('plugin'=>null,'controller'=>'pages','action'=>'view',$page['Page']['idurl'])); ?>
						<p class='indent sortable_hide'>
							<?= $this->Html->summary($page['Page']['content']); ?>
						</p>
					</div>
					<? } ?>
				</div>
				<? } else { # admin + no pages ?>
				<div class='nodata'>
				There are no pages yet. 
				</div>
				<? } ?>
			</div>
		</div>
		
		<? if(count($updates['pages']) > 1 && $this->Html->can_edit()) { ?>
		<script>
			$('#Pages_sorter').sorter('.pagelist',{ prefix: "members" });
		</script>
		<? } ?>
	</div>

	<div class='col-md-12'>
		<?= $this->element("../Homepages/updates",array()); ?>
	</div>
</div>
<div class='clear'></div>

<? } ?>
