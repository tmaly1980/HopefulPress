<? if(empty($links) && !empty($category)) { ?>
<div class='nodata'>
	There are no links in this category yet.
	<? if($this->Html->can_edit()) { #$in_admin && $this->Admin->access()) { ?>
		<?= $this->Html->link("Add the first link", array('user'=>1,'controller'=>'links','action'=>'add','link_category_id'=>$category['LinkCategory']['id']), array('class'=>'')); ?>
		<br/>
	<? } ?>
	<? if($this->Html->can_edit($category['LinkCategory'])) { # Admin or creator ?>
		<?= $this->Html->blink("delete", "Delete this category", array('user'=>'user','controller'=>'link_categories','action'=>'delete',$category['LinkCategory']['id']), array('class'=>'controls btn-danger')); ?>
	<? } ?>
</div>
<div id="<?= !empty($category) ? "LinkCategory_".$category['LinkCategory']['id'] : "" ?>" class='linklist'>
	<span>&nbsp;</span>
</div>
<? } else if (empty($links)) { ?>
<div class='nodata'>
	There are no links yet.
</div>

<? } else { # Links w/ or w/o cat, or nothing if new ?>
<div id="<?= !empty($category) ? "LinkCategory_".$category['LinkCategory']['id'] : "" ?>" class='linklist'>
	<? foreach($links as $link) { 
		if(!empty($link["Link"])) { $link = $link['Link']; } ?>
		<div id="Link_<?= $link['id'] ?>" class="Link item block margintop20">
			<div>
					<?= $this->Html->link($link['title'], '/links/follow?goto='.$link['url'], array('class'=>'medium','title'=>(!empty($pid)?$link['description']:""))); ?>
				<? if($this->Html->can_edit($link)) { #!empty($in_admin) && ($this->Admin->access($link))) { ?>
				<span class='paddingleft10'>
					<?= $this->Html->blink("edit", null, array('user'=>1,'controller'=>'links','action'=>'edit',$link['id']), array('class'=>'btn-xs btn-primary','title'=>'Edit/change this link')); ?>
					&nbsp;
					<?= $this->Html->blink("delete", null, array('user'=>1,'controller'=>'links','action'=>'delete',$link['id']), array('class'=>'btn-xs btn-danger','title'=>'Remove this link','confirm'=>'Are you sure you want to remove this link?')); ?>
				</span>
				<? } ?>
				<div class='clear'></div>
				<? if(empty($pid)) { ?>
				<p class="indent">
					<?= !empty($link['description']) ? $link['description'] : "&nbsp;" ?>
				</p>
				<? } ?>
			</div>
		</div>
	<? } ?>
</div>
<? } ?>
<span>&nbsp;</span><!-- needs filler to sortable -->
