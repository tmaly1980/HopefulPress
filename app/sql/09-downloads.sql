#DROP TABLE IF EXISTS downloads;
CREATE TABLE IF NOT EXISTS downloads
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED, # eventually, only admins or creator can modify a download

	#download_category_id INTEGER UNSIGNED,
	#ix INTEGER UNSIGNED, # Only Admins can rearrange downloads (to prioritize). Others just append to end of list.

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


#DROP TABLE IF EXISTS download_categories;
CREATE TABLE IF NOT EXISTS download_categories
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	name VARCHAR(50),
	ix INTEGER UNSIGNED, # For sorting

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS download_pages;
CREATE TABLE IF NOT EXISTS download_pages
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	title VARCHAR(64),

	introduction TEXT,

	created DATETIME,
	modified DATETIME
);
