<? if(empty($resources) && !empty($category)) { ?>
<div class='nodata'>
	<? if($this->Html->can_edit()) { #$in_admin && $this->Admin->access()) { ?>
	There are no resources in this category yet.
		<?= $this->Html->link("Add the first resource", array('user'=>1,'controller'=>'resources','action'=>'add','resource_category_id'=>$category['ResourceCategory']['id']), array('class'=>'')); ?>
		<br/>
	<? } ?>
	<? if($this->Html->can_edit($category['ResourceCategory'])) { # Admin or creator ?>
		<?= $this->Html->blink("delete", "Delete this category", array('user'=>'user','controller'=>'resource_categories','action'=>'delete',$category['ResourceCategory']['id']), array('class'=>'controls btn-danger white')); ?>
	<? } ?>
</div>
<div id="<?= !empty($category) ? "ResourceCategory_".$category['ResourceCategory']['id'] : "" ?>" class='resourcelist'>
	<span>&nbsp;</span>
</div>
<? } else if (empty($resources)) { ?>
<div class='nodata'>
	There are no resources yet.
</div>

<? } else { # Resources w/ or w/o cat, or nothing if new ?>
<div id="<?= !empty($category) ? "ResourceCategory_".$category['ResourceCategory']['id'] : "" ?>" class='resourcelist'>
	<? foreach($resources as $resource) { 
		if(!empty($resource["Resource"])) { $resource = $resource['Resource']; } ?>
		<div id="Resource_<?= $resource['id'] ?>" class="Resource item block margintop20">
			<div>
				<h4>
					<?= $resource['title'] ?>

					<? if($this->Html->can_edit($resource)) { #!empty($in_admin) && ($this->Admin->access($resource))) { ?>
					<span class='paddingleft10'>
						<?= $this->Html->blink("edit", null, array('user'=>1,'controller'=>'resources','action'=>'edit',$resource['id']), array('class'=>'white  btn-xs btn-primary','title'=>'Edit/change this resource')); ?>
						&nbsp;
						<?= $this->Html->blink("delete", null, array('user'=>1,'controller'=>'resources','action'=>'delete',$resource['id']), array('class'=>'white  btn-xs btn-danger','title'=>'Remove this resource','confirm'=>'Are you sure you want to remove this resource?')); ?>
					</span>
					<? } ?>
				</h4>
				<div class='clear'></div>
				<? if(!empty($resource['address'])) { ?>
				<div>
					<?= $this->Html->link($resource['address'], "http://maps.google.com/?q=".$resource['address']); ?>
				</div>
				<? } ?>
				<? if(!empty($resource['phone'])) { ?>
				<div><?= $resource['phone'] ?></div>
				<? } ?>
				<? if(!empty($resource['url'])) { ?>
				<div>
					<?= $this->Html->link((!preg_match("/:\/\//", $resource['url']) ? "http://":"").$resource['url']); ?>
				</div>
				<? } ?>
				<? if(!empty($resource['description'])) { ?>
				<p class="indent">
					<?= !empty($resource['description']) ? $resource['description'] : "&nbsp;" ?>
				</p>
				<? } ?>
			</div>
		</div>
	<? } ?>
</div>
<? } ?>
<span>&nbsp;</span><!-- needs filler to sortable -->
