/* 

people needing general help, not sure what to do...

categories on home page
	top 10 questions  per category... more link

one answer - by me.... they can  accept/reject and I modify, and they get notified again
	and keep revising until they accept

* there needs to be some sort of commenting/conversation to get to point of acceptance
		
search functionality!!! not external
actsAs = ElasticSearchIndex.ElasticSearchIndexable

"notify me when the question is answered"

??? allowing questioner to accept or reject an answer?
	- way to show that happy or confused (need further help)
	- if rejected, needs notes/replies to then accept

allowing peers to vote up/down multiple answers?

most popular questions: simple increment, ok if viewed multiple times by same person

list in order asked...

*/
#DROP TABLE IF EXISTS support_questions;
CREATE TABLE IF NOT EXISTS support_questions
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED, # Person who asked (If i not add myself)

	question_category_id INTEGER UNSIGNED,

	title VARCHAR(200),

	description TEXT,

	answer TEXT,

	answerer_user_id INTEGER UNSIGNED, # So we can display...
	answered DATETIME, # When answer added...

	views INTEGER UNSIGNED NOT NULL DEFAULT 0,

	status VARCHAR(20), # accepted, rejected

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_comments;
CREATE TABLE IF NOT EXISTS support_question_comments
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED, # me or them

	question_id INTEGER UNSIGNED,

	comment TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_categories;
CREATE TABLE IF NOT EXISTS support_question_categories
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,

	title VARCHAR(250),

	description TEXT,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_question_tags;
CREATE TABLE IF NOT EXISTS support_question_question_tags
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,

	question_id INTEGER UNSIGNED,
	question_tag_id INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_tags;
CREATE TABLE IF NOT EXISTS support_question_tags
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,

	tag VARCHAR(100),

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_notifications;
CREATE TABLE IF NOT EXISTS support_question_notifications
(
	id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id INTEGER UNSIGNED, # them

	question_id INTEGER UNSIGNED,

	created DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS support_question_likes; /* For satisfied answers */
CREATE TABLE IF NOT EXISTS support_question_likes
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	question_id INTEGER UNSIGNED,
	user_id INTEGER UNSIGNED, # Who did the liking

	created DATETIME,
	modified DATETIME
);

