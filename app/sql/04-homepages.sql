#DROP TABLE IF EXISTS homepages;
CREATE TABLE IF NOT EXISTS homepages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	title VARCHAR(128),
	introduction TEXT,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE homepages ADD sidebar_content TEXT; # Affiliate ads, etc.

