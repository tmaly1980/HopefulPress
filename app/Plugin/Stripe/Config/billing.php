<?
$config['Billing'] = array(
	'trial_days'=>30,
	'domain_registration'=>array(
		'id'=>'domain_registration',
		'amount'=>1000, # CENTS
		'currency'=>'usd',
		'interval'=>'year',
		'name'=>'Yearly Domain Registration for Hopeful Press Website',
	),
	# Load pricing on page from HERE
	'plans' => array(
		'basic'=>array( 
			'id'=>'basic',
			'amount'=>500, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Basic Plan',
		),
		'basic_yearly'=>array( 
			'id'=>'basic_yearly',
			'amount'=>5400, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Basic Plan',
		),
		'dedicated'=>array( 
			'id'=>'dedicated',
			'amount'=>1000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Dedicated Plan',
		),
		'dedicated_yearly'=>array( 
			'id'=>'dedicated_yearly',
			'amount'=>10200, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Dedicated Plan',
		),
		'unlimited'=>array( 
			'id'=>'unlimited',
			'amount'=>2000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Unlimited Plan',
		),
		'unlimited_yearly'=>array( 
			'id'=>'unlimited_yearly',
			'amount'=>18000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Unlimited Plan',
		),
	)
);

