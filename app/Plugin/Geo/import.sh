#!/bin/sh

mysql -u portal --password=portal1 --local-infile=1 portal --execute="LOAD DATA LOCAL INFILE 'zip_codes.csv' INTO TABLE zip_codes FIELDS TERMINATED BY ',' IGNORE 1 LINES (zip_code,state_abbreviation,latitude,longitude,city,state);"

