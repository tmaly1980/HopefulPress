#DROP TABLE IF EXISTS rescue_homepages;
CREATE TABLE IF NOT EXISTS rescue_homepages (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	adopt_title VARCHAR(36),
	donate_title VARCHAR(36),
	volunteer_title VARCHAR(36),
	foster_title VARCHAR(36),

	adopt_photo_id  INTEGER UNSIGNED,
	donate_photo_id  INTEGER UNSIGNED,
	volunteer_photo_id  INTEGER UNSIGNED,
	foster_photo_id  INTEGER UNSIGNED,

	adopt_summary VARCHAR(200),
	donate_summary VARCHAR(200),
	volunteer_summary VARCHAR(200),
	foster_summary VARCHAR(200),

	created DATETIME,
	modified DATETIME
);


