<?
/*
	How do we determine effective CTA's? Which variant is more successful?

	page: one/many urls of future pages (marketing_page_views) with same visit_id
	model: one/many models with visit_id set

*/
$config['ABAction'] = array( # Define effective calls to action, for stats

	"www/static/home"=>array(
		"page"=>array("/pages/features","/pages/demo", "/pages/signup","/signup"),
		"model"=>"Site", # May as well see if better homepage (screenshot vs photo) creates more signups....
	),
	#
	"sites/signup"=>array( # No variants defined yet, but might be worth checking at some point...
		"model"=>"Site",
	),
);
