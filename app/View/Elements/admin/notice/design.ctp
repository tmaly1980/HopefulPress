<? if(!in_array($this->layout,array('admin','plain'))) { # Non-sensical. ?>
<div class='alert alert-info'>

<?= $this->Html->link($this->Html->fa("paint-brush").
	" Manage Your Design", "/admin/site_designs/view",array('class'=>'btn btn-primary controls margin5')); ?>
	to customize your website theme, colors, logo, and header.
</div>
<? } ?>
