#DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,
	email VARCHAR(64),
	first_name VARCHAR(24),
	last_name VARCHAR(24),

	page_photo_id INTEGER UNSIGNED,

	password VARCHAR(128),

	invite VARCHAR(128), 
	invited DATETIME,


	admin BOOL DEFAULT FALSE,
	manager BOOL DEFAULT FALSE,

	last_login DATETIME, 
	login_count INT UNSIGNED,

	created DATETIME,
	modified DATETIME
);

INSERT INTO users SET email='tomas@malysoft.com', site_id = 0, first_name = 'Tomas', last_name = 'Maly', manager = 1, password='e6a5472d23cce177738cacbf7b1b80d17ef2cea1dcfd2c8c3d17ed3400bac510';
INSERT INTO users SET email='tomas@hopefulpress.com', site_id = 0, first_name = 'Tomas', last_name = 'Maly', manager = 1, password='e6a5472d23cce177738cacbf7b1b80d17ef2cea1dcfd2c8c3d17ed3400bac510';
