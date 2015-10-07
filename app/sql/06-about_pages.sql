#DROP TABLE IF EXISTS about_pages;
CREATE TABLE IF NOT EXISTS about_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	title VARCHAR(128),

	page_photo_id INTEGER UNSIGNED, 

	overview TEXT,
	history TEXT,

	bio_title VARCHAR(32),

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS about_page_bios;
CREATE TABLE IF NOT EXISTS about_page_bios (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	name VARCHAR(64),
	title VARCHAR(64),
	page_photo_id INTEGER UNSIGNED, # easiest to borrow everything, including cropping
	description TEXT,
	ix INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE about_pages ADD mission TEXT;
