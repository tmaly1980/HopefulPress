#DROP TABLE IF EXISTS member_pages;
CREATE TABLE IF NOT EXISTS member_pages
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,

	page_photo_id INTEGER UNSIGNED,

	title VARCHAR(255),

	description TEXT,

	enabled BOOL DEFAULT FALSE,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE news_posts ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE events ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE pages ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE photo_albums ADD members_only BOOL DEFAULT FALSE;
#ALTER TABLE photos ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE links ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE downloads ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE link_pages ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE download_pages ADD members_only BOOL DEFAULT FALSE;
ALTER TABLE link_categories ADD members_only BOOL DEFAULT FALSE;
