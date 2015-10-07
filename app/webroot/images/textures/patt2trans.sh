#!/bin/sh -x

if [ "$1" -eq "" ]; then
	echo "$0 image/path.png";
	exit;
fi

IMG=$1
FILE=$(basename $IMG)
OUT=trans/$FILE

convert $IMG -fuzz 8% -transparent white PNG32:$OUT 
