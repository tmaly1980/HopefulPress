<?
# How to parse video URL's
$config['VideoConfig'] = array(
	'youtube.com'=>array(
		'regex'=>'youtube.com.*v[=\/]([a-zA-Z0-9_-]+)',
            	//'url_template'   => 'http://gdata.youtube.com/feeds/api/videos/%s?fs=1&hd=1&egm=0&rel=0&loop=0&start=0&version=2&autoplay=0&showinfo=0&disablekb=0&wmode=transparent',
		'url_template' => "http://www.youtube.com/embed/%s?wmode=transparent",
	),
	'vimeo.com'=>array(
		'regex'=>'...',
		'url_template'=>"http://player.vimeo.com/video/%s?color=ffffff&wmode=transparent",
	),
);
