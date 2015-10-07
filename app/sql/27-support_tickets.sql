/*
Support tickets
	Allow for users to say "I'm experiencing this problem too"
	LIST (ie severity)
	Email me when  someone adds to notification list

Once I've set "Resolved - Needs Testing", participants (commenters, notifieds) get emailed to test.
	if they (submitter) reject, ask for comment, auto-set to pending,  and email me.

Commenters and Notifieds... contact when comment added or status set to resolved

"Deferred" status means won't make changes (could be a feature request)
	should set "reason" field for an explanation
	and (maybe) deferred_until date (if I know for sure)

Once set "estimated", email participants of date (give them satisfaction)
	- "Pending" status based on "estimated" but not "resolved"

??? What IF others disagree with resolved status, still experiencing ?
	- allow for comments / notifications even after resolved. ie 6 months later even.

	- Notify participants of "this issue has been re-opened"
		I have to manually set back. ("Re-open ticket" button)
*/

#DROP TABLE IF EXISTS support_tickets;
CREATE TABLE IF NOT EXISTS support_tickets
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED,

	title VARCHAR(200),
	description TEXT,

	estimated DATETIME,
	resolved DATETIME,
	tech_user_id INTEGER UNSIGNED,
	confirmed DATETIME,
	deferred DATETIME,

	reason TEXT, # FROM ME

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_ticket_comments;
CREATE TABLE IF NOT EXISTS support_ticket_comments
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED, # me or them

	ticket_id INTEGER UNSIGNED,

	comment TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_ticket_notifications;
CREATE TABLE IF NOT EXISTS support_ticket_notifications
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED, # them

	ticket_id INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);
