DROP TABLE IF EXISTS tracker_events;
CREATE TABLE tracker_events (
  id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ip VARCHAR(16),
  city VARCHAR(32), 
  state VARCHAR(48), 
  country VARCHAR(64), 
  zipCode VARCHAR(12),

  session_id VARCHAR(128),
  browser VARCHAR(128),
  url VARCHAR(255),
  prefix VARCHAR(32),
  event VARCHAR(32),
  created datetime,
  modified datetime
);

