#DROP TABLE IF EXISTS rescue_education_indices;
CREATE TABLE IF NOT EXISTS rescue_education_indices
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	title VARCHAR(200),

	page_photo_id INTEGER UNSIGNED,

	content TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS rescue_education_pages;
CREATE TABLE IF NOT EXISTS rescue_education_pages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	user_id INTEGER UNSIGNED, # Owner

	page_photo_id INTEGER UNSIGNED,

	title VARCHAR(128),
	url VARCHAR(128),
	ix INTEGER UNSIGNED,

	#parent_id INTEGER UNSIGNED, # NOT YET

	content TEXT,

	created DATETIME,
	modified DATETIME
);

