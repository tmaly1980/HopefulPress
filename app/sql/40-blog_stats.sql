DROP TABLE IF EXISTS blog_visits;
CREATE TABLE blog_visits (
  id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ip VARCHAR(16),
	city VARCHAR(32), 
	state VARCHAR(48), 
	country VARCHAR(64), 
	zipCode VARCHAR(12),

  session_id VARCHAR(128),
  browser VARCHAR(128),
  url VARCHAR(255),
  query_string VARCHAR(255),
  page_views INTEGER UNSIGNED DEFAULT '0',
  start datetime,
  end datetime,
  refkeywords VARCHAR(255),
  start_page_id INTEGER UNSIGNED,
  end_page_id INTEGER UNSIGNED,
  refinternal BOOL DEFAULT FALSE,
  refdomain VARCHAR(64),
  refqs VARCHAR(64),
  refpath VARCHAR(64),
  fake BOOL DEFAULT FALSE,
  created datetime,
  modified datetime
);

DROP TABLE IF EXISTS blog_page_views;
CREATE TABLE blog_page_views (
  id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  blog_visit_id INTEGER UNSIGNED,
  ip VARCHAR(16),
  session_id VARCHAR(128),
  url VARCHAR(255),
  title VARCHAR(255),
  query_string VARCHAR(255),
  refkeywords VARCHAR(255),
  controller VARCHAR(32),
  action VARCHAR(16),
  page_id VARCHAR(255),
  refinternal BOOL DEFAULT FALSE,
  refdomain VARCHAR(64),
  refqs VARCHAR(64),
  refpath VARCHAR(64),
  fake BOOL DEFAULT FALSE,
  created datetime,
  modified datetime
);

###########################################################
# "Share this page"
#
#DROP TABLE IF EXISTS blog_shares;
CREATE TABLE IF NOT EXISTS blog_shares
(
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

	social_site VARCHAR(64), # External site, or email (WONT keep email details), code....
	page_url VARCHAR(255), # Our url
	title VARCHAR(255), # Our title... (so we dont have to look up)

	model VARCHAR(32),
	model_id INTEGER UNSIGNED,

	session_id VARCHAR(255), # Who they are (we can aggregate to see big sharers)

	ip_address VARCHAR(16), 

	city VARCHAR(32), 
	state VARCHAR(48), 
	country VARCHAR(64), 
	zipCode VARCHAR(12),

	created DATETIME,
	modified DATETIME
);
ALTER TABLE blog_visits CHANGE browser browser VARCHAR(255);
ALTER TABLE blog_visits ADD referer  VARCHAR(255);
ALTER TABLE blog_page_views ADD referer  VARCHAR(255);
