#DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	title VARCHAR(255),
	url VARCHAR(255),
	dates VARCHAR(255),
	event_location_id INTEGER UNSIGNED,
	event_contact_id INTEGER UNSIGNED,

	page_photo_id INTEGER UNSIGNED,

	start_date DATE,
	end_date DATE,

	start_time TIME,
	end_time TIME,

	summary VARCHAR(200),

	details TEXT,

	created DATETIME,
	modified DATETIME
);


#DROP TABLE IF EXISTS event_locations;
CREATE TABLE IF NOT EXISTS event_locations
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	name VARCHAR(255),
	address VARCHAR(64),
	address_2 VARCHAR(32),
	city VARCHAR(32),
	state VARCHAR(32),
	zip_code VARCHAR(8),
	country VARCHAR(32),
	phone VARCHAR(24),

	comments TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS event_contacts;
CREATE TABLE IF NOT EXISTS event_contacts
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	name VARCHAR(255),
	phone VARCHAR(24),
	email VARCHAR(64),
	comments TEXT,

	created DATETIME,
	modified DATETIME
);


