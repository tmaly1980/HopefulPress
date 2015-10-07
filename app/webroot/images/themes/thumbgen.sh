#!/bin/sh -x
#
# webkit2png package (installed into /usr/local/bin):
#
# https://github.com/AdamN/python-webkit2png/

#echo "REMEMBER, USE Uppercase Theme";

SITE="rescue"; # example
THEME="$1"
WINDOW="1024 900"
CROP="400x400"
SCALE="200x200"

if [ "$THEME" = "" ]; then
	echo "USAGE: $0 ThemeName";
	exit;7
fi

webkit2png --geometry=$WINDOW -o large/$THEME.png "http://$SITE.hp.malysoft.com/?no_controls=1&theme=$THEME&color1="
convert large/$THEME.png -crop $CROP+0+0 -scale $SCALE+0+0 $THEME.png
