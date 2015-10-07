#DROP TABLE IF EXISTS newsletter_subscribers;
CREATE TABLE IF NOT EXISTS newsletter_subscribers
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	email VARCHAR(128),
	first VARCHAR(24),
	last VARCHAR(24),

	type VARCHAR(16), # SOMEDAY Subscriber, Donor, Volunteer, etc

	confirm_code VARCHAR(36),
	confirmed BOOL DEFAULT FALSE,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS newsletter_messages;
CREATE TABLE IF NOT EXISTS newsletter_messages
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	send DATETIME, # Scheduled for; can be null, ie not ready to send

	message_type VARCHAR(36), # we format most of the outer layout, they specify here what kind of email, ie newsletter, emergency fundraiser, etc
	# pretty much customizing layout and autopopulated blocks,etc
	# I come up with different email formats as I figure them out or people ask...

	data TEXT, # Content pieces for this email notification (json struct)

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS newsletter_queued_emails;
CREATE TABLE IF NOT EXISTS newsletter_queued_emails
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	site_id INTEGER UNSIGNED,

	newsletter_message_id INTEGER UNSIGNED,
	newsletter_subscriber_id INTEGER UNSIGNED, # For data to fill-in

	#status VARCHAR(255) NOT NULL DEFAULT 'pending',  # pending, sent

	sent DATETIME, # If null, needs to be send.

	created DATETIME,
	modified DATETIME
);
