#DROP TABLE IF EXISTS todo_tasks;
CREATE TABLE IF NOT EXISTS todo_tasks
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	parent_id INTEGER UNSIGNED, # Parent meta-task

	milestone_id INTEGER UNSIGNED,
	release_id INTEGER UNSIGNED,
	module_id INTEGER UNSIGNED,

	title VARCHAR(200),

	status ENUM('New','Deferred','Invalid','In Progress','Needs Testing','Closed') DEFAULT 'New',
	priority ENUM('Wishlist','Low','Normal','High','Urgent','Emergency') DEFAULT 'Normal',

	type ENUM('Bug Fix','Task','Feature Request','Wish List') DEFAULT 'Task',

	description TEXT,
	impact TEXT,

	due_date DATETIME,
	deferred_date DATETIME,
	invalid_date DATETIME,
	in_progress_date DATETIME,
	needs_testing_date DATETIME,
	closed_date DATETIME,

	created  DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS todo_milestones;
CREATE TABLE IF NOT EXISTS todo_milestones
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	release_id INTEGER UNSIGNED, 

	title VARCHAR(200),

	description TEXT,

	status ENUM('New','In Progress','Needs Testing','Closed') DEFAULT 'New',

	start_date DATE,
	finish_date DATE,

	created  DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS todo_releases;
CREATE TABLE IF NOT EXISTS todo_releases
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	title VARCHAR(200),

	description TEXT,

	launch_date DATE,

	created  DATETIME,
	modified DATETIME
);

#DROP TABLE IF EXISTS todo_modules;
CREATE TABLE IF NOT EXISTS todo_modules
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	title VARCHAR(200),

	description TEXT,

	created  DATETIME,
	modified DATETIME
);

# MULTIPLE PROJECTS (hopefulpress rescues vs grower portal, etc)
# SOMEDAY - now just place module inside project.

#ALTER TABLE todo_tasks ADD project_id INTEGER UNSIGNED;
#ALTER TABLE todo_milestones ADD project_id INTEGER UNSIGNED;
#ALTER TABLE todo_tasks ADD project_id INTEGER UNSIGNED;
#ALTER TABLE todo_tasks ADD project_id INTEGER UNSIGNED;
#ALTER TABLE todo_tasks ADD project_id INTEGER UNSIGNED;
