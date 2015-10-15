<? if(empty($typeName)) { $typeName = $type; } ?>
<div class='row'>
<? if(!empty($faqs) || !empty($pages) || $this->Html->can_edit()) { ?>
<div class='col-md-6'>
	<? if(!empty($faqs) || $this->Html->can_edit()) { ?>
	<div  class='margintop25'>
		<h3 id='faq'>FAQ's</h3>
		<? if(empty($faqs)) { ?>
		<div class='dashed alert alert-info'>
			<?= $this->Html->link("Add frequently asked questions", array('user'=>1,'controller'=>"{$type}_faqs",'action'=>'add')); ?>
			for commonly asked <?=$typeName ?> questions
		</div>
		<? } else { ?>
			<?= $this->element("../".Inflector::camelize($type)."Faqs/list"); ?>
			<? if($this->Html->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>
				<?= $this->Html->add("Add question", array("user"=>1,"controller"=>"{$type}_faqs","action"=>"add"),array('id'=>'AddFaq','short'=>false,'class'=>'btn-xs')); ?>
			<? } ?>
		<? } ?>
	</div>
	<? } ?>
	
	<? if(!empty($pages) || $this->Html->can_edit()) { ?>
	<div class='margintop25'>
		<h3 id='pages'>More Information</h3>
		<? if(empty($pages)) { ?>
		<div class='dashed alert alert-info'>
			<?= $this->Html->link("Add pages", array('user'=>1,'controller'=>"{$type}_pages",'action'=>'add')); ?>
			with further <?=$typeName ?> details
		</div>
		<? } else  { ?>
			<?= $this->element("../".Inflector::camelize($type)."Pages/list"); ?>
			<? if($this->Html->can_edit()) { ?>
				<?= $this->Html->add("Add page", array("user"=>1,"controller"=>"{$type}_pages","action"=>"add"),array('short'=>false,'class'=>'btn-xs')); ?>
			<? } ?>
		<? } ?>
	</div>
	<? } ?>
</div>
<? } ?>
<? if(!empty($downloads) || !empty($nav["{$type}FormEnabled"]) || $this->Html->can_edit()) { ?>
<div class='col-md-6'>
	<? if(!empty($downloads) || $this->Html->can_edit()) { ?>
	<div class='margintop25'>
		<h3 id='downloads'>Files</h3>
		<? if(empty($downloads)) { ?>
		<div class='dashed alert alert-info'>
			<?= $this->Html->link("Add files", array('user'=>1,'controller'=>"{$type}_downloads",'action'=>'add')); ?>
			such as <?=$typeName ?> forms (PDF), etc.
		</div>
		<? } else  { ?>
			<?= $this->element("../".Inflector::camelize($type)."Downloads/list"); ?>
			<? if($this->Html->can_edit()) { # By default, only owner can add sub page - and then can re-assign ?>
				<?= $this->Html->add("Add file", array("user"=>1,"controller"=>"{$type}_downloads","action"=>"add"),array('id'=>'AddFile','short'=>false,'class'=>'btn-xs')); ?>
			<? } ?>
		<? } ?>
	</div>
	<? } ?>
</div>
<? } ?>

<? if($typeName != 'sponsorship' && (!empty($nav["{$type}FormEnabled"]) || $this->Html->can_edit())) { ?>
<div class='<?= empty($form_link) ? "col-md-12" : "col-md-6" ?> margintop25'>
		<? if($this->Html->can_edit()) { ?>
		<div class="right">
		<?= $this->Html->remove("Disable", array('user'=>1,'controller'=>"{$type}_forms",'action'=>'disable'), array('class'=>'','short'=>false)); ?>
		</div>
		<? } ?>
	<h3 id='<?=$type?>Form'>Online <?=Inflector::humanize($type); ?> Form</h3>
	<div class='clear'></div>
	<? if(empty($nav["{$type}FormEnabled"])) { ?>
	<div class='dashed alert alert-info'>
		<?= $this->Html->add("Enable online $type form", array('user'=>1,'controller'=>"{$type}_forms",'action'=>'enable'), array('class'=>'','short'=>false)); ?>
		to receive <?=$type?> requests via email
		<!-- go to customize form when enable -->
	</div>
	<? } else { ?>
	<div class='margintop25'>
		<!-- customize link -->
		<? if(!empty($form_link)) { ?>
			<?= $this->Html->link("Fill out our online $type form", array('controller'=>Inflector::pluralize("rescue_".(!empty($person)?$person:$type)),'action'=>'add'), array('class'=>'btn btn-warning white')); ?>
		<? } ?>
		<? if(empty($form_link)) { ?>
			<?= $this->element("../".Inflector::pluralize(Inflector::camelize("rescue_$type"))."/edit"); ?>
		<? } ?>
		<div class='clear'></div>
	</div>
	<? } ?>
</div>
<? } ?>
</div>
