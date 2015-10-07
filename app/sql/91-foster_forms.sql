DROP TABLE IF EXISTS rescue_foster_forms;
CREATE TABLE IF NOT EXISTS rescue_foster_forms
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	title VARCHAR(150),

	introduction TEXT,

	#  TODO FLAGS to disable/enable relevent sections/fields

	#

	created DATETIME,
	modified DATETIME
);

DROP TABLE IF EXISTS rescue_foster_requests;
CREATE TABLE IF NOT EXISTS rescue_foster_requests
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	adoptable_id INTEGER UNSIGNED, # If specific...

	data TEXT,

	status ENUM ('Received','Pending','Accepted','Denied') DEFAULT 'Received',

	created DATETIME,
	modified DATETIME
);

