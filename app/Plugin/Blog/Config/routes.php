<?
# WPW BLOG SITE
$http_host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
$domains = 'wordpeacewebsites.com|wpw.malysoft.com|hopefulpress.com|hp.malysoft.com';

if(preg_match("/^(www[.])?($domains)/", $http_host)) 
{ # On www, NOT custom hostname
	#Router::connect("/", array('plugin'=>'blog','controller'=>'posts','action'=>'index'));
	Router::connect("/blog", array('plugin'=>'blog','controller'=>'topics','action'=>'index'));
	#Router::connect("/blog/*", array('plugin'=>'blog','controller'=>'posts','action'=>'view'));
	Router::connect("/manager/blog", array('manager'=>true,'plugin'=>'blog','controller'=>'topics','action'=>'index'));
}
