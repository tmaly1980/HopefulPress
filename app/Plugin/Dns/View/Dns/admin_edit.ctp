<? $domain = $this->Site->get("domain"); ?>
<? $this->assign("page_title", (!empty($domain)?"Update ":"Add ")."Website Domain"); ?>
<?= $this->Form->create("Site", array('url'=>$this->here)); ?>
<div class='form'>
	<?= $this->Form->input_group("domain", array('label'=>'My existing domain name is:','before'=>"http://",'class'=>'large','focus'=>1)); ?>

	<?= $this->Html->link("Search for an available domain", array('admin'=>1,'action'=>'search')); ?>
	<br/>
	<br/>
	
	<?= $this->Form->save("Save domain", array('cancel'=>array('action'=>'index'))); ?>
</div>
<?= $this->Form->end(); ?>
