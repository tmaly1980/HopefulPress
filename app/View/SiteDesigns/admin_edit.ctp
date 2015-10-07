<div class='form padding10 maxwidth900'> <!-- so doesn't flow off screen once sidebar opens -->
<h3>Choose design options below</h3>
<? /*
<?= $this->Html->script("Core.toggler"); ?>
<a href='javascript:void(0)' class='toggler' data-toggle-fa-icon="cogs fa-2x" data-toggle-title="Customize Design"></a>
*/ ?>

<!-- 
	<? print_r($this->request->data); ?>
-->
<? Configure::load("SiteDesigns"); $themes = Configure::read("SiteDesigns.themes"); ?>

<?= $this->Form->create("SiteDesign",array('url'=>array('action'=>'edit'),'id'=>'SiteDesignForm','class'=>'')); ?>
<ul id='design_tabs' class='nav nav-pills'>
	<li class='active'> <a class='controls' href='#design_theme' id=''>Theme</a> </li>
	<li class=''> <a class='controls' href='#design_colors' id=''>Colors</a> </li>
	<li class=''> <a class='controls' href='#design_header' id=''>Header</a> </li>
	<!-- <li class=''> <a href='#design_fonts' id=''>Fonts</a> </li> -->
	<!--<li class=''> <a href='#design_background' id=''>Background</a> </li>-->
</ul>
<div class='tab-content'>
	<div id='design_theme' class='tab-pane active'>
		<?= $this->Form->thumbs("theme",array('options'=>$themes,'path'=>'/images/themes')); ?>
	</div>
	<div id='design_colors' class='tab-pane'>
		<?= $this->Form->color("color1"); ?>
		<?#= $this->Form->input("color2"); ?>
	</div>
	<div id='design_background' class='tab-pane'>
		BACKGROUND - image, texture (default from theme)
	</div>
	<div id='design_fonts' class='tab-pane'>
		FONTS - h1, h2, h3, nav, body text
	</div>
	<div id='design_header' class='tab-pane'>
		<!-- HEADER - image, texture (default from theme) -->


		<div class='row'>
			<div id="design_logo" class='col-md-4'>
				<?= $this->element("../SiteDesignLogos/edit"); ?>

				<?= $this->Form->input("logo_size",array('label'=>false,'default'=>175,'options'=>array(100=>'Small',175=>'Medium',250=>'Large'))); ?>
			</div>
			<div class='col-md-8'>
				<?= $this->Form->input("title",array('label'=>false,'placeholder'=>'Header Title','default'=>$current_site['Site']['title'],'class'=>'large')); ?>
				<?= $this->Form->input("subtitle",array('label'=>false,'placeholder'=>'subtitle')); ?>

				<?= $this->Form->input("right_text",array('rows'=>3,'label'=>false,'placeholder'=>'Text on right')); ?>
			</div>
		</div>
	
		<!--
		FONTS
	
		INLINE EDIT FOR HEADER ? mode enabled from sidebar
		-->
		<div class='row'>
			<?= $this->Form->input_group("facebook_url",array('div'=>'col-md-6','placeholder'=>'http://facebook.com/YourPage','label'=>false,'before'=>$this->Html->fa('facebook fa-lg'))); ?>
			<?= $this->Form->input_group("twitter_url",array('div'=>'col-md-6','placeholder'=>'http://twitter.com/YourPage','label'=>false,'before'=>$this->Html->fa('twitter fa-lg'))); # instagram? google+? pinterest? WAIT UNTIL ASKED ?>
		</div>
	</div>

	<div class='row'>
		<div class='preview alert alert-info' style='display:none;'>
			Loading Preview... <?= $this->Html->fa("spinner fa-spin fa-lg"); ?>
		</div>
	</div>
	
	<div class='form-controls' style='display: none;'>
			<?= $this->Form->save("Save Changes",array('cancel'=>$this->here)); ?>
	</div>
</div>

<?= $this->Form->end(); ?>
</div>

<script>
$('#design_tabs a').click(function(e) {
	e.preventDefault();
	$(this).tab('show');
});

$('#SiteDesignForm').delaySubmit(function() // INSTANT PREVIEW
{
	$('#SiteDesignForm').trigger('preview');
}, 1000);
$('#SiteDesignForm').on('preview', function() {
	console.log("PREVIEWING...");
	$('#SiteDesignForm .preview').show();
	$('#SiteDesignForm .form-controls').show();
	// Load current page. Being requested via AJAX should strip off admin wrapper

	//var url = window.location.href+"?preview=1"; // So form is included.
	var url = "/admin/homepages/preview"; // So form is included.
	$('#main').load(url, $('#SiteDesignForm').serializeObject(), function() {
		$('#SiteDesignForm .preview').hide();
	});
});
</script>
