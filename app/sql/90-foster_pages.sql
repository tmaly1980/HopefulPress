DROP TABLE IF EXISTS rescue_foster_overviews;
CREATE TABLE IF NOT EXISTS rescue_foster_overviews
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	title VARCHAR(250),
	page_photo_id INTEGER UNSIGNED,

	introduction TEXT,

	created DATETIME,
	modified DATETIME
);

DROP TABLE IF EXISTS rescue_foster_pages;
CREATE TABLE IF NOT EXISTS rescue_foster_pages
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	title VARCHAR(250),
	url  VARCHAR(250),
	page_photo_id INTEGER UNSIGNED,

	content TEXT,

	created DATETIME,
	modified DATETIME
);

DROP TABLE IF EXISTS rescue_foster_downloads;
CREATE TABLE IF NOT EXISTS rescue_foster_downloads
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED, # eventually, only admins or creator can modify a download

	#download_category_id INTEGER UNSIGNED,
	ix INTEGER UNSIGNED, # Only Admins can rearrange downloads (to prioritize). Others just append to end of list.

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

DROP TABLE IF EXISTS rescue_foster_faqs;
CREATE TABLE IF NOT EXISTS rescue_foster_faqs
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	ix INTEGER UNSIGNED,

	question VARCHAR(250),
	answer TEXT,

	created DATETIME,
	modified DATETIME
);
