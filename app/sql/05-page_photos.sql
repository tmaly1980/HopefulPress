#DROP TABLE IF EXISTS page_photos;
CREATE TABLE IF NOT EXISTS page_photos
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	#photo_url VARCHAR(255), # from internet

	title VARCHAR(255),
	path VARCHAR(255),
	thumb_path VARCHAR(255),
	caption TEXT,
	filename VARCHAR(255),
	ext VARCHAR(6),
	type VARCHAR(24), # MIME
	size INTEGER UNSIGNED,
	ix INTEGER UNSIGNED NULL,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE pages ADD page_photo_id INTEGER UNSIGNED;
ALTER TABLE homepages ADD page_photo_id INTEGER UNSIGNED;


ALTER TABLE page_photos ADD crop_x INTEGER UNSIGNED, ADD crop_y INTEGER UNSIGNED;
ALTER TABLE page_photos ADD crop_w INTEGER UNSIGNED, ADD crop_h INTEGER UNSIGNED;



/*
ALTER TABLE news_posts ADD page_photo_id INTEGER UNSIGNED;
ALTER TABLE events ADD page_photo_id INTEGER UNSIGNED;
ALTER TABLE about_pages ADD page_photo_id INTEGER UNSIGNED;
*/

######################
/* ALTER TABLE page_photos ADD width INTEGER UNSIGNED, ADD height INTEGER UNSIGNED;
ALTER TABLE page_photos ADD view_hidden BOOL DEFAULT FALSE;
ALTER TABLE page_photos ADD align VARCHAR(10) DEFAULT 'right';
*/
