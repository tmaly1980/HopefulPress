/* This is just a prototype, keep shit simple; can fine tune and add features/convenience later  */

#DROP TABLE IF EXISTS rescue_adoptables;
CREATE TABLE IF NOT EXISTS rescue_adoptables
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	name VARCHAR(24),
	page_photo_id INTEGER UNSIGNED,

	biography TEXT,
	special_needs TEXT,

	species VARCHAR(24), # Keys in config file
	breed VARCHAR(24), # Stored in config file
	mixed_breed BOOL DEFAULT FALSE,
	breed2 VARCHAR(24),

	birthdate DATETIME,
	gender ENUM('Male','Female'),
	weight_lbs INTEGER UNSIGNED, # Currently
	adult_size ENUM('Small','Medium','Large') DEFAULT 'Medium',
	energy_level ENUM('','Low','Medium','High') DEFAULT '',

	#primary_color VARCHAR(16),
	#secondary_color VARCHAR(16),

	child_friendly BOOL DEFAULT TRUE,
	minimum_child_age INTEGER UNSIGNED,  # Years old minimum
	cat_friendly BOOL DEFAULT TRUE,
	dog_friendly BOOL DEFAULT TRUE,

	neutered_spayed BOOL DEFAULT TRUE,
	fostered BOOL DEFAULT FALSE, # should we always show 'foster me'? or only when  not fostered or near foster expiration?

	date_fosterable DATE, # When do we start displaying this button?
	# Could be new person not already within organization
	date_available DATE, # Blank = NOW; always show button since they may be willing to wait...

	adoption_cost INTEGER UNSIGNED, # Make them enter each time, and adjust if needed. No sense in putting somewhere totally else

	status ENUM('Available','Retreived','Pending Adoption','Adopted') DEFAULT 'Available',

	date_adopted DATE,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS rescue_adoptable_photos;
CREATE TABLE IF NOT EXISTS rescue_adoptable_photos
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	adoptable_id INTEGER UNSIGNED,
	ix INTEGER UNSIGNED NULL,

	photo_url VARCHAR(255), # from internet

	title VARCHAR(255),
	path VARCHAR(255),
	filename VARCHAR(255),
	ext VARCHAR(6),
	type VARCHAR(24), # MIME
	size INTEGER UNSIGNED,

	caption TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS rescue_adoptable_videos;
CREATE TABLE IF NOT EXISTS rescue_adoptable_videos
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	
	adoptable_id INTEGER UNSIGNED,

	title VARCHAR(255),

	# If external.
	video_url VARCHAR(255), 
	video_id VARCHAR(32),  
	video_site VARCHAR(64),
	thumbnail_url VARCHAR(255), 
	preview_url VARCHAR(255), # 480x360

	# If internal on disk.
	path VARCHAR(255), # Original
	name VARCHAR(255),
	filename VARCHAR(255),
	size INTEGER UNSIGNED,
	ext VARCHAR(6),
	type VARCHAR(24),
	description TEXT, # 1st para is 'summary'

	created DATETIME,
	modified DATETIME
);


# ADOPTION FORM/APPLICATION TODO

#DROP TABLE IF EXISTS rescue_success_stories;
CREATE TABLE IF NOT EXISTS rescue_success_stories
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	adoptable_id INTEGER UNSIGNED,

	page_photo_id INTEGER UNSIGNED,

	title VARCHAR(200), # Summary

	content TEXT,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE rescue_adoptables ADD enable_sponsorship BOOL DEFAULT FALSE;
ALTER TABLE rescue_adoptables ADD sponsorship_goal INTEGER UNSIGNED;
ALTER TABLE rescue_adoptables ADD sponsorship_goal_recurring BOOL DEFAULT FALSE;
ALTER TABLE rescue_adoptables ADD sponsorship_details TEXT;

ALTER TABLE rescue_adoptables ADD microchip VARCHAR(250);

#DROP TABLE IF EXISTS rescue_adoptable_owners;
CREATE TABLE IF NOT EXISTS rescue_adoptable_owners
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	primary_first_name VARCHAR(24),
	primary_last_name VARCHAR(24),

	secondary_first_name VARCHAR(24),
	secondary_last_name VARCHAR(24),

	address VARCHAR(36),
	address2 VARCHAR(36),
	city VARCHAR(36),
	state VARCHAR(36),
	zip_code VARCHAR(36),

	primary_phone VARCHAR(24),
	secondary_phone VARCHAR(24),

	created DATETIME,
	modified DATETIME
);


ALTER TABLE rescue_adoptables  ADD adoptable_owner_id INTEGER UNSIGNED;
