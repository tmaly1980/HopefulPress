#DROP TABLE IF EXISTS projects;
CREATE TABLE IF NOT EXISTS projects
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED, # Project owner.


	url VARCHAR(255),
	page_photo_id INTEGER UNSIGNED,
	ix INTEGER UNSIGNED, # To sort 

	title VARCHAR(255),

	description TEXT,

	#published DATETIME,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS project_users;
CREATE TABLE IF NOT EXISTS project_users
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	project_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED NOT NULL,

	admin BOOL DEFAULT FALSE,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE news_posts ADD project_id INTEGER UNSIGNED;
ALTER TABLE events ADD project_id INTEGER UNSIGNED;
ALTER TABLE pages ADD project_id INTEGER UNSIGNED;
ALTER TABLE photo_albums ADD project_id INTEGER UNSIGNED;
#ALTER TABLE photos ADD project_id INTEGER UNSIGNED;
ALTER TABLE links ADD project_id INTEGER UNSIGNED;
ALTER TABLE downloads ADD project_id INTEGER UNSIGNED;
ALTER TABLE link_pages ADD project_id INTEGER UNSIGNED;
ALTER TABLE download_pages ADD project_id INTEGER UNSIGNED;
ALTER TABLE link_categories ADD project_id INTEGER UNSIGNED;
