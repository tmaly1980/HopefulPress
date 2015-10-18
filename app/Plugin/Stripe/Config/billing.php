<?
$metadata = array(
	'basic'=> array(
		'title'=>'Basic Plan',
		'description'=>"
			<ul class='spaced'>
				<li>Ready-to-go website, fill-in-the-blanks
				<li>Customize your own design and content (news, events, photos, etc)
				<li>Ready-to-go web forms for adoptions, fostering, and volunteering
				<li>Collect unlimited online donations &amp; sponsorships
				<li>Newsletter/Email mailing list
				<li>Use your own web address <b>YourRescue.org</b>
				<li>Up to 5 volunteer and foster user accounts to update content
				<li>Up to 10 featured adoptable listings at a time
			</ul>
			"
	),
	'dedicated'=>array(
		'title'=>'Dedicated Plan',
		'description'=>"
			Everything with the Basic Plan, plus:
			<ul class='spaced'>
				<li>Up to 25 featured adoptable listings at a time
				<li>Up to 10 volunteer and foster user accounts
			</ul>
			"
	),
	'unlimited'=>array(
		'title'=>'Unlimited Plan',
		'description'=>"
		Everything with the Dedicated Plan, plus:
			<ul class='spaced'>
				<li>Unlimited featured adoptables
				<li>Unlimited volunteer user accounts
				<li>Email/Webmail accounts @YourRescue.org
			</ul>
			"
	),
);

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
	'planList'=>array('basic'=>true,'dedicated'=>true,'unlimited'=>true),
	# oddly it doubles  the keys into values if just a list, for config sensibilities?
	'plans' => array(
		'basic'=>array( 
			'id'=>'basic',
			'amount'=>1000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Basic Plan',
			'metadata'=>$metadata['basic'],
		),
		'basic_yearly'=>array( 
			'id'=>'basic_yearly',
			'amount'=>10800, # CENTS; $108/yr; 10% Off.
			'currency'=>'usd',
			'interval'=>'year',
			'name'=>'Hopeful Press Website Hosting - Basic Plan (Yearly)',
			'metadata'=>$metadata['basic']
		),
		'dedicated'=>array( 
			'id'=>'dedicated',
			'amount'=>1500, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Dedicated Plan',
			'metadata'=>$metadata['dedicated']
		),
		'dedicated_yearly'=>array( 
			'id'=>'dedicated_yearly',
			'amount'=>15300, # CENTS; 15% off
			'currency'=>'usd',
			'interval'=>'year',
			'name'=>'Hopeful Press Website Hosting - Dedicated Plan (Yearly)',
			'metadata'=>$metadata['dedicated']
		),
		'unlimited'=>array( 
			'id'=>'unlimited',
			'amount'=>2000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Unlimited Plan',
			'metadata'=>$metadata['unlimited']
		),
		'unlimited_yearly'=>array( 
			'id'=>'unlimited_yearly',
			'amount'=>18000, # CENTS; 25% off
			'currency'=>'usd',
			'interval'=>'year',
			'name'=>'Hopeful Press Website Hosting - Unlimited Plan (Yearly)',
			'metadata'=>$metadata['unlimited']
		),
	)
);

