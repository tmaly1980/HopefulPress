<?
App::uses("WwwAppModel", "Www.Model");
class IntakeSurvey extends WwwAppModel
{
	var $actsAs = array(
		'Core.CommaSeparated'
	);

	var $order = 'IntakeSurvey.created DESC';

	var $belongsTo = array(
		'LogoFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'logo_id'),
		'SampleFosterFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_foster_form_file_id'),
		'SampleAdoptionFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_adoption_form_file_id'),
		'SampleVolunteerFormFile'=>array('className'=>'Www.IntakeSurveyFile','foreignKey'=>'sample_volunteer_form_file_id'),

	);

	var $species = array('Dogs','Cats','Birds','Horses');
	var $basic_pages = array('Home Page','Contact Information','Contact Form','About Page (Mission/History)','Staff/Member/Volunteer List', 'Resources (Organizations/Websites)');
	var $homepage_content = array('Recent News','Upcoming Events','Photos','Videos','Current Adoptables','Recent Success Stories','Affiliate Ads');
	var $adoption_pages = array('Adoptable Listings','Success Stories','Adoption Procedures','Adoption Form');
	var $foster_pages = array('Foster Information','Foster Application Form');
	var $volunteer_pages = array('Volunteer Information','Volunteer Application Form');
	var $donation_features = array('Receive Online Donations','Wish List','Sponsor Adoptables (Long-Term Fosters)','Recurring Donations');
	var $types_of_email_messages = array('Newsletters','Event Reminders','Transport Alerts','Fundraising Alerts','New Adoptables','Success Stories');


}
