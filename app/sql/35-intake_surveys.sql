DROP TABLE IF EXISTS www_intake_surveys;
CREATE TABLE IF NOT EXISTS www_intake_surveys
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	first_name VARCHAR(32),
	last_name VARCHAR(32),
	email VARCHAR(64),
	organization VARCHAR(64),
	existing_website VARCHAR(128),
	existing_website_provider VARCHAR(128),
	existing_website_costs VARCHAR(128),
	existing_website_details TEXT, # Pros/cons/etc

	domain VARCHAR(64),
	already_own_domain BOOL,

	species SET('Dogs','Cats','Birds','Horses'),
	other_species TEXT,
	breeds VARCHAR(200),


	basic_pages SET('Home Page','Contact Information','Contact Form','About Page (Mission/History)','Staff/Member/Volunteer List', 'Resources (Organizations/Websites)'),

	homepage_content SET('Recent News','Upcoming Events','Photos','Videos','Current Adoptables','Recent Success Stories','Affiliate Ads'),

	adoption_pages SET('Adoptable Listings','Success Stories','Adoption Procedures','Adoption Form'),

	other_adoption_pages TEXT,
	sample_adoption_form_file_id INTEGER UNSIGNED,

	foster_pages SET('Foster Information','Foster Application Form'),
	sample_foster_form_file_id INTEGER UNSIGNED,
	foster_page_details TEXT,

	volunteer_pages SET('Volunteer Information','Volunteer Application Form'),
	sample_volunteer_form_file_id INTEGER UNSIGNED,
	volunteer_page_details TEXT,

	educational_pages TEXT,
	other_pages TEXT,

	donation_features SET('Receive Online Donations','Wish List','Sponsor Adoptables (Long-Term Fosters)','Recurring Donations'),
	current_donation_system VARCHAR(128),
	donation_details TEXT,

	facebook_page VARCHAR(128),
	twitter_page VARCHAR(128),
	other_social_media_links TEXT,

	want_mailing_list BOOL,
	example_mailing_list_content TEXT,
	current_mailing_list_provider VARCHAR(128),
	current_total_subscribers INTEGER,
	types_of_email_messages SET('Newsletters','Event Reminders','Transport Alerts','Fundraising Alerts','New Adoptables','Success Stories'),


	# DESIGN STUFF
	logo_id INTEGER UNSIGNED,
	design_name VARCHAR(36),
	other_design_url VARCHAR(250),
	desired_colors VARCHAR(250),
	color1 VARCHAR(7),
	color2 VARCHAR(7),
	color3 VARCHAR(7),

	status ENUM('New','Discussing','Created','Deferred') DEFAULT 'New',
	site_id INTEGER UNSIGNED,

	internal_notes TEXT,

	created DATETIME,
	modified  DATETIME
);

DROP TABLE IF EXISTS www_intake_survey_files;
CREATE TABLE IF NOT EXISTS www_intake_survey_files
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

        title VARCHAR(100),

	# Standard file upload fields...
	name VARCHAR(255), # What user computer named it
	filename VARCHAR(255), # Random name on our system
        path VARCHAR(255),
        size INTEGER UNSIGNED,
        ext VARCHAR(6),
	type VARCHAR(32),

        description TEXT,
	created DATETIME,
	modified DATETIME
);
