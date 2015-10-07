#DROP TABLE IF EXISTS pages;
CREATE TABLE IF NOT EXISTS pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	user_id INTEGER UNSIGNED, # Owner
	title VARCHAR(128),
	url VARCHAR(128),

	content TEXT,

	created DATETIME,
	modified DATETIME
);

# topics have parent_id = null
# other pages have parent_id = 0
ALTER TABLE pages ADD parent_id INTEGER UNSIGNED;

ALTER TABLE pages ADD ix INTEGER UNSIGNED;


/*

ALTER TABLE pages ADD draft_id INTEGER UNSIGNED;
ALTER TABLE pages ADD published DATETIME;

#############################
#DROP TABLE IF EXISTS about_pages;
CREATE TABLE IF NOT EXISTS about_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(128),

	intro TEXT,
	history TEXT,
	content TEXT,

	created DATETIME,
	modified DATETIME
); # Add details later

#############################
#DROP TABLE IF EXISTS contact_pages;
CREATE TABLE IF NOT EXISTS contact_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(128),

	created DATETIME,
	modified DATETIME
); # Add details later

#############################
#DROP TABLE IF EXISTS home_pages;
CREATE TABLE IF NOT EXISTS home_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(128),

	introduction TEXT,

	created DATETIME,
	modified DATETIME
);
*/
