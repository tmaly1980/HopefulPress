DROP TABLE IF EXISTS rescue_adoption_forms;
CREATE TABLE IF NOT EXISTS rescue_adoption_forms
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

DROP TABLE IF EXISTS rescue_adoption_requests;
CREATE TABLE IF NOT EXISTS rescue_adoption_requests
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	adoptable_id INTEGER UNSIGNED,

	data TEXT,

	status ENUM ('Received','Pending','Accepted','Denied') DEFAULT 'Received',

	created DATETIME,
	modified DATETIME
);

