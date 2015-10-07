DROP TABLE IF EXISTS marketing_visits;
CREATE TABLE marketing_visits (
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

DROP TABLE IF EXISTS marketing_page_views;
CREATE TABLE marketing_page_views (
  id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  marketing_visit_id INTEGER UNSIGNED,
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


ALTER TABLE marketing_visits ADD campaign_code VARCHAR(16);
ALTER TABLE marketing_visits ADD campaign_subcode VARCHAR(16);

ALTER TABLE marketing_page_views ADD campaign_code VARCHAR(16);
ALTER TABLE marketing_page_views ADD campaign_subcode VARCHAR(16);

ALTER TABLE marketing_visits CHANGE browser browser VARCHAR(255);
ALTER TABLE marketing_visits ADD referer  VARCHAR(255);
ALTER TABLE marketing_page_views ADD referer  VARCHAR(255);

ALTER TABLE sites ADD campaign_code VARCHAR(16), ADD campaign_subcode VARCHAR(16);

