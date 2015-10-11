#!/bin/sh

for f in *.jpg *.png; do 
	echo $f; 
	convert $f -resize 200x -crop 200x200+0+0 thumbs/$f; 
	convert $f -resize 600x -crop 600x600+0+0 large/$f; 
done

