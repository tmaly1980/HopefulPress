#DROP TABLE IF EXISTS contact_pages;
CREATE TABLE IF NOT EXISTS contact_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	title VARCHAR(128),

	page_photo_id INTEGER UNSIGNED, 

	phone VARCHAR(24),
	alternate_phone VARCHAR(24),
	fax VARCHAR(24),
	email VARCHAR(64),

	address VARCHAR(128),
	city VARCHAR(32),
	state VARCHAR(16),
	zip_code VARCHAR(8),

	show_map BOOL DEFAULT FALSE,
	contacts_title VARCHAR(32),

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS contacts;
CREATE TABLE IF NOT EXISTS contacts (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	name VARCHAR(64),
	title VARCHAR(64),
	phone VARCHAR(24),
	alternate_phone VARCHAR(24),
	email VARCHAR(64),
	details TEXT,
	ix INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);
