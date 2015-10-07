#DROP TABLE IF EXISTS sites;
CREATE TABLE IF NOT EXISTS sites (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	hostname VARCHAR(24),
	domain VARCHAR(64),
	title VARCHAR(64),
	user_id INTEGER UNSIGNED, # Owner

	plan VARCHAR(12),
	stripe_id VARCHAR(128),

	disabled DATETIME,
	upgraded DATETIME,
	internal BOOL DEFAULT FALSE,

	created DATETIME,
	modified DATETIME
);

ALTER TABLE sites ADD layout VARCHAR(24);
ALTER TABLE sites ADD theme VARCHAR(24);
# Rescue.rescue, etc
