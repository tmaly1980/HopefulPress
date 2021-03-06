#DROP TABLE IF EXISTS news_posts;
CREATE TABLE IF NOT EXISTS news_posts
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED NOT NULL,
	user_id INTEGER UNSIGNED,

	draft_id INTEGER UNSIGNED,
	published DATETIME DEFAULT NULL,

	page_photo_id INTEGER UNSIGNED,

	title VARCHAR(255),
	url VARCHAR(255),

	summary VARCHAR(200),

	content TEXT,

	created DATETIME,
	modified DATETIME
);
