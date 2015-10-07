#!/bin/sh -x

rsync --progress --exclude=app/webroot/uploads --exclude=.git --exclude=app/tmp --exclude=logs --exclude=docs -av  /local/sites/hp/* /local/sites/hp/.htaccess  tomas@hopefulpress.com:/var/www/hp/
