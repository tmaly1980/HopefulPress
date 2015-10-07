<? $this->assign("page_title", "Domain Search/Register"); ?>
<?= $this->Form->create('Site', array('id'=>'SearchForm','url'=>"/admin/dns/dns/search", 'Xupdate'=>'DomainDetails')); ?>
<div class='form'>
	<?= $this->Form->input_group("domain", array('label'=>'Find an available domain to register:','before'=>'<span class="large">http://</span>','class'=>'large width300','focus'=>1)); ?>

	<?= $this->Html->link("Use an existing domain", array('admin'=>1,'action'=>'edit')); ?>
	<br/>
	<br/>

	<?= $this->Form->save('Search',array('Xcancel'=>array('action'=>'view'),'XXXjson'=>1,'XXXupdate'=>'Site_dns','class'=>'')); ?>
</div>
<?= $this->Form->end(); ?>
<script>
$('#SearchForm').submit(function() {
	//$('#SearchForm').clearErrors();
	$('#SearchResults').html('');
});
</script>

<div id="SearchResults">
<? if(!empty($domain)) { ?>
	<?= $this->Form->create("Site", array('validate'=>false,'url'=>"/admin/dns/dns/edit",'Xupdate'=>'Site_dns')); ?>
	<div id='' class='form'>
		<?= $this->Form->hidden("domain",array('value'=>$domain)); ?>
	<? if(!empty($valid)) { # Don't bother showing result if domain is bogus ?>
		<? if(empty($available)) { ?>
			<div class="warn red color marginleft50 medium bold">This address is not available</div>
			<br/>
			<br/>
			<? if(!empty($valid)) { ?>
			If this is your domain, <?= $this->Html->link("click here to still use it", array('action'=>'edit','?'=>array('domain'=>$domain)), array('Xupdate'=>'Site_dns','class'=>'color')); ?>
			<br/>
			<? } ?>
		<? } else { ?>
			<div class="green color medium bold marginleft50 good">
				<?= $domain ?> is available!
				<?= $this->Form->save("Register this domain", array('Xupdate'=>'Site_dns','cancel'=>false)); ?>
			</div>


		<? } ?>
	<? } ?>
	</div>
	<?= $this->Form->end(); ?>
<? } ?>
</div>
