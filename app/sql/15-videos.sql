#DROP TABLE IF EXISTS videos;
CREATE TABLE IF NOT EXISTS videos
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	user_id INTEGER UNSIGNED,

	title VARCHAR(255),

	# If external.
	video_url VARCHAR(255), 
	video_id VARCHAR(32),  
	video_site VARCHAR(64),
	thumbnail_url VARCHAR(255), 

	update_id INTEGER UNSIGNED,

	# If internal on disk.
	path VARCHAR(255), # Original
	name VARCHAR(255),
	filename VARCHAR(255),
	size INTEGER UNSIGNED,
	ext VARCHAR(6),
	type VARCHAR(24),
	description TEXT, # 1st para is 'summary'


	project_id INTEGER UNSIGNED,

	video_category_id INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE videos ADD preview_url VARCHAR(255); # 480x360

##############################################

#DROP TABLE IF EXISTS video_categories;
CREATE TABLE IF NOT EXISTS video_categories
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	user_id INTEGER UNSIGNED,

	project_id INTEGER UNSIGNED,

	title VARCHAR(255),
	description TEXT,

	created DATETIME,
	modified DATETIME
);
#####################################
