<?php 
class WwwSchema extends CakeSchema {

	public $file = 'schema_0.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $www_intake_survey_files = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'size' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'ext' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 6, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $www_intake_surveys = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'organization' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'existing_website' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'existing_website_provider' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'existing_website_costs' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'existing_website_details' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'species' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 6, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'other_species' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'homepage_content' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 22, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'adoption_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 19, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'sample_adoption_form_file_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'sample_foster_form_file_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'foster_page_details' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'sample_volunteer_form_file_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'volunteer_page_details' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'educational_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'other_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'wish_list_page' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'receive_online_donations' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'current_donation_system' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'facebook_page' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'twitter_page' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'other_social_media_links' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'want_mailing_list' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'example_mailing_list_content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'current_mailing_list_provider' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'current_total_subscribers' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'logo_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'design_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'other_design_url' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'other_adoption_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foster_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 23, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'volunteer_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 26, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'domain' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'already_own_domain' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'breeds' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'types_of_email_messages' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 18, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'color1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'color2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'color3' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'site_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 24, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'internal_notes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'basic_pages' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 19, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'donation_features' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 24, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'donation_details' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
