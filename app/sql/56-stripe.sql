#DROP TABLE IF EXISTS stripe_credentials;
CREATE TABLE IF NOT EXISTS stripe_credentials
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	token_type VARCHAR(16),
	stripe_publishable_key VARCHAR(128),
	scope VARCHAR(16),
	livemode BOOL DEFAULT FALSE,
	stripe_user_id VARCHAR(128),
	refresh_token VARCHAR(128),
	access_token VARCHAR(128),

	created DATETIME,
	modified DATETIME
);

