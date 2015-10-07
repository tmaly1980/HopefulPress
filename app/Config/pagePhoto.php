<?
$config['PagePhoto'] = array(
	'AboutPageBio'=>array(
		'scaledWidth'=>'200x200',
		'placeholder'=>'/images/add-a-person.png',
		'nocaption'=>true
	),
	'User'=>array(
		'scaledWidth'=>'200x200',
		'placeholder'=>'/images/add-a-person.png',
		'nocaption'=>true
	),
	'Project'=>array(
		'scaledWidth'=>'200x200'
	),
	'Rescue'=>array(
		'wh'=>'100x100',
		'scaledWidth'=>'100x100',
		'placeholder'=>'/images/add-a-logo.png?',  # Stupid chrome not refreshing cache, even  with ctrl+shift+r
		'nocaption'=>true,
		'photoModel'=>'RescueLogo',
		# controller, and primaryKey should be auto-calculated
		'btnSize'=>'btn-sm',
		'thing'=>'logo',
		'plugin'=>false,
		#'onEditLoad'=>"$('#SiteDesignForm').trigger('preview');",
	),
	'SiteDesign'=>array(
		'wh'=>'100x100',
		'scaledWidth'=>'100x100',
		'placeholder'=>'/images/add-a-logo.png?',  # Stupid chrome not refreshing cache, even  with ctrl+shift+r
		'nocaption'=>true,
		'photoModel'=>'SiteDesignLogo',
		'controller'=>'site_design_logos',
		#'primaryKey'=>'site_design_logo_id',
		'btnSize'=>'btn-sm',
		'thing'=>'logo',
		'plugin'=>false,
		'onEditLoad'=>"$('#SiteDesignForm').trigger('preview');",
	),
	# Need dot syntax since we have a 'rescue' controller.
	'Adoptable'=>array(
		'nocaption'=>true,
		'photoModel'=>'AdoptablePhoto',
		'plugin'=>false,
	),

);
