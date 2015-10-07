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
);

$config['Billing']['plans'] = array(
		'standard'=>array( 
			'id'=>'standard',
			'amount'=>1000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Basic Plan',
			'metadata'=>array(
				'title'=>'Basic Plan',
				'description'=>"
				<ul class='spaced'>
				<li>Ready-to-go website, fill-in-the-blanks
				<li>Customize your own design and content
				<li>List up to 10 featured animals
				<li>Ready-to-go web forms for adoptions, fostering, and volunteering
				<li>Collect online donations/sponsorships
				<li>Newsletter/Email mailing list
				<li>Up to 5 user accounts
				<li>Use your own domain <b>YourRescue.org</b>
				</ul>
				"
			),
		),
		'advanced'=>array( 
			'id'=>'advanced',
			'amount'=>1500, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Advanced Plan',
			'metadata'=>array(
				'title'=>'Advanced Plan',
				'description'=>"
				Everything with the Basic Plan, plus:
				<ul class='spaced'>
				<li>Up to 20 featured animals
				<li>Up to 10 user accounts
				<li>Email/Webmail @YourRescue.org
				</ul>
				"
			)
		),
		'unlimited'=>array( 
			'id'=>'unlimited',
			'amount'=>2000, # CENTS
			'currency'=>'usd',
			'interval'=>'month',
			'name'=>'Hopeful Press Website Hosting - Unlimited Plan',
			'metadata'=>array(
				'title'=>'Unlimited Plan',
				'description'=>"
				Everything with the Advanced Plan, plus:
				<ul class='spaced'>
				<li>Unlimited user accounts
				<li>Unlimited email accounts @YourRescue.org
				<li>Unlimited featured animals
				</ul>
				"
			)
		),
	);

# Now duplicate plans for yearly discount.
$config['Billing']['yearlyPlans'] = array();
foreach($config['Billing']['plans'] as $planName=>$planDetails) { 
	$planDetails['id'] = "{$planName}_yearly";
	$planDetails['interval'] = 'year';
	$planDetails['amount'] *= (12*0.75); # 25% off
	$planDetails['name'] .= " (Annual Billing)"; 
	$planDetails['metadata']['title'] .= " (Annual Billing)";

	$config['Billing']['yearlyPlans'][$planDetails['id']] = $planDetails;
}
?>
