<? if (empty($faqs)) { ?>
<div class='nodata'>
	There are no frequently asked questions yet.
</div>
<? } else { ?>
<div id='Faqs'>
	<? foreach($faqs as $faq) { 
		if(!empty($faq["FosterFaq"])) { $faq = $faq['FosterFaq']; } ?>
		<div id="Faq_<?= $faq['id'] ?>" class="Faq item block margintop20">
				<h4>
					<?= $this->Html->link($faq['question'], 'javascript:void(0)', array('class'=>'question')); ?>

				<? if($this->Html->can_edit($faq)) { #!empty($in_admin) && ($this->Admin->access($faq))) { ?>
				<span class='paddingleft10'>
					<?= $this->Html->edit(null, array('user'=>1,'controller'=>'foster_faqs','action'=>'edit',$faq['id']), array('class'=>'btn-xs btn-primary','title'=>'Edit/change this question')); ?>
					&nbsp;
					<?= $this->Html->delete(null, array('user'=>1,'controller'=>'foster_faqs','action'=>'delete',$faq['id']), array('class'=>'btn-xs btn-danger','title'=>'Remove this question','confirm'=>'Are you sure you want to remove this question?')); ?>
				</span>
				<? } ?>
				</h4>
				<div class='clear'></div>
				<div class='Answer' style='display:none;'>
					<?= !empty($faq['answer']) ? $faq['answer'] : "<i>This question has not been answered yet</i>"; ?>
				</div>
		</div>
	<? } ?>
	<script>
	$(document).ready(function()  {
		$('.Faq h4 a').click(function() {
			console.log(this);
			console.log($(this).closest('.Faq'));
			console.log($(this).closest('.Faq').find('.Answer'));
			$(this).closest('.Faq').find('.Answer').toggle();
		});

	});
	</script>
</div>
<? } ?>
<span>&nbsp;</span><!-- needs filler to sortable -->
