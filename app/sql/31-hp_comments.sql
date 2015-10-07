##################################
# Shared comments among various models.
#DROP TABLE IF EXISTS hp_comments;
CREATE TABLE IF NOT EXISTS hp_comments
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	parent_id INTEGER UNSIGNED, # Can reply to a persons comment.
	model VARCHAR(64), # Table
	model_id INTEGER UNSIGNED, # Record ID

	user_id INTEGER UNSIGNED,
	#site_id INTEGER UNSIGNED, # Either in case for a specific site, OR  to track user site 
	# UNSURE FOR NOW - seems not very necessary - and cant enable auto_site_id if non-support, etc...
	# keying to model/model_id should 'just work', regardless of site (no extraneous data)

	# OR:
	name VARCHAR(64),
	email VARCHAR(64),
	notify BOOL DEFAULT FALSE,

	title VARCHAR(255),
	content TEXT,

	created DATETIME,
	modified DATETIME
);
