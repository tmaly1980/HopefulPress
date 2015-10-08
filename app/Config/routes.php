<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	#Router::connect('/', array('controller' => 'static_pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	#Router::connect('/static_pages/*', array('controller' => 'static_pages', 'action' => 'display'));


###########################
Router::parseExtensions('json','rss', 'css');

# XXX TODO handle universal SSL for donations  (windows 5 mobile doesn't allow for wildcards!)
# Map: https://hopefulpress.com/lostdog/donations/.... => site=lostdog .... 
# involves setting named parameter, which HostInfo can read???? (not quite request->params['named'] ready)
# So we may just have to UPDATE HTTP_HOST / REQUEST_URI ourselves! call HostInfo::rewriteGlobalSSL()

##############################

# IN A SITE

App::uses("HostInfo", "Core.Lib");
if(!HostInfo::site_specified())
{
	Router::connectNamed(array('rescue'));

	# ADOPT homepage.
	Router::connect('/', array('controller' => 'adoptables', 'action' => 'index')); 
	Router::connect('/adopt', array('controller' => 'adoptables', 'action' => 'index')); 

	Configure::write("www",true);
	Configure::write("layout",'default'); # Moved to default...
	Configure::write("portal",true);
	# Multisite is off.

	#Configure::write("layout","Rescue.rescue");
	Configure::write("theme","Rescue"); # This will be unnecessary once actual view files exist  within plugin

	# View content within rescue
	Router::connect('/rescue/:rescue', array('controller' => 'rescues', 'action' => 'view'),array('rescue'=>'[\w_-]+'));
		
	
	# CSS
	Router::connect('/rescue/:rescue/style', array('controller' => 'rescues', 'action' => 'style'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/style/*', array('controller' => 'rescues', 'action' => 'style'),array('rescue'=>'[\w_-]+'));

	# OTHER PAGES
	Router::connect('/rescue/:rescue/about', array('controller' => 'rescues', 'action' => 'about'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/contact', array('controller' => 'rescues', 'action' => 'contact'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/donate', array('plugin'=>'donation','controller' => 'donation_pages', 'action' => 'view'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/donate/:action', array('plugin'=>'donation','controller' => 'donations'),array('rescue'=>'[\w_-]+')); # IPN and form
	Router::connect('/rescue/:rescue/donate/:action/*', array('plugin'=>'donation','controller' => 'donations'),array('rescue'=>'[\w_-]+')); # IPN and form

	Router::connect('/rescue/:rescue/news', array('controller' => 'news_posts', 'action' => 'index'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/news/*', array('controller' => 'news_posts', 'action' => 'view'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/photos', array('controller' => 'photo_albums', 'action' => 'index'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/photos/*', array('controller' => 'photo_albums', 'action' => 'view'),array('rescue'=>'[\w_-]+'));

	# Adoptable.
	Router::connect('/adoptable/:id', array('controller'=>'adoptables','action'=>'view'),array('id'=>'\d+'));
	Router::connect('/rescue/:rescue/adoptable/:id', array('controller'=>'adoptables','action'=>'view'),array('rescue'=>'[\w_-]+','id'=>'\d+'));
	Router::connect('/rescue/:rescue/adoptable/:id/:action', array('controller'=>'adoptables'),array('rescue'=>'[\w_-]+','id'=>'\d+'));#View
	Router::connect('/rescue/:rescue/adoptable/*', array('controller'=>'adoptables','action'=>'view'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescuer/rescue/:rescue/adoptable/:id/:action', array('rescuer'=>1,'controller'=>'adoptables'),array('rescue'=>'[\w_-]+','id'=>'\d+')); # Edit
	Router::connect('/rescuer/rescue/:rescue/adoptable/add', array('rescuer'=>1,'action'=>'add'),array('rescue'=>'[\w_-]+'));

	#
	Router::connect('/rescue/:rescue/:controller', array(),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/:controller/:action', array(),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescue/:rescue/:controller/:action/*', array(),array('rescue'=>'[\w_-]+'));

	# MANAGING SPECIALIZATIONS
	Router::connect('/rescuer/rescue/:rescue/specializations', array('rescuer'=>1,'controller'=>'rescue_specializations'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescuer/rescue/:rescue/specializations/:action', array('rescuer'=>1,'controller'=>'rescue_specializations'),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescuer/rescue/:rescue/specializations/:action/*', array('rescuer'=>1,'controller'=>'rescue_specializations'),array('rescue'=>'[\w_-]+'));

	# Update rescuer content.
	Router::connect('/rescuer/rescue/:rescue/:action', array('rescuer'=>1,'controller'=>'rescues'),array('rescue'=>'[\w_-]+')); # edit, etc
	Router::connect('/rescuer/rescue/:rescue/:controller/:action', array('rescuer'=>1),array('rescue'=>'[\w_-]+'));
	Router::connect('/rescuer/rescue/:rescue/:controller/:action/*', array('rescuer'=>1),array('rescue'=>'[\w_-]+'));

	Router::connect('/rescuer/:plugin/rescue/:rescue/:controller/:action', array('rescuer'=>1),array('rescue'=>'[\w_-]+'));

	# We cannot generalize with ':prefix', we have to be explicit. Or loop over all prefixes found and specify rules each.

	# Stuff for all users (may as well)
	Router::connect('/user/rescue/:rescue/:controller', array('user'=>1),array('rescue'=>'[\w_-]+'));
	Router::connect('/user/rescue/:rescue/:controller/:action', array('user'=>1),array('rescue'=>'[\w_-]+'));
	Router::connect('/user/rescue/:rescue/:controller/:action/*', array('user'=>1),array('rescue'=>'[\w_-]+'));

	/*
	# We could be failing to catch because general routing creates duplicate entry...
	# RESCUE CONTENT
	Router::connect('/', array('plugin'=>'www','controller' => 'static', 'action' => 'index'));

	# NEW
	Router::connect('/portal', array('plugin'=>'rescue','controller' => 'portal', 'action' => 'index'));

	Router::connect('/websites', array('plugin'=>'www','controller' => 'static', 'action' => 'index'));
	Router::connect('/websites/*', array('plugin'=>'www','controller' => 'static', 'action' => 'view'));
	Configure::write("layout","plain");

	#Router::connect('/', array('plugin'=>'www','controller' => 'static', 'action' => 'view','home'));

	Router::connect('/manager', array('manager'=>1,'plugin'=>'www','controller' => 'dashboard','action'=>'index'));
	Router::connect('/blog', array('plugin'=>'blog','controller' => 'posts', 'action' => 'index'));
	Router::connect('/pages/*', array('plugin'=>'www','controller' => 'static', 'action' => 'view'));

	Router::connect('/contact', array('plugin'=>'www','controller' => 'contact_requests', 'action' => 'add'));
	Router::connect('/consult', array('plugin'=>'www','controller' => 'intake_surveys', 'action' => 'add'));
	Router::connect('/signup', array('controller' => 'sites', 'action' => 'signup'));
	Router::connect('/signup/*', array('controller' => 'sites', 'action' => 'signup'));

	#Router::connect('/:ref', array('plugin'=>'www','controller' => 'static', 'action' => 'view','home'),array('ref'=>'r\d+'));
	# Not very meaningful as a weird code

	#Configure::write("layout","www");
	Configure::write("www",true);
	Configure::write("favicon", "/www/images/logo.ico");
	Configure::write("rss", "/blog/posts/index.rss");
	*/
} else if (HostInfo::hostname() == 'todo') { 
	Router::connect('/', array('plugin'=>'todo','controller' => 'tasks'));
	Router::connect('/tasks', array('plugin'=>'todo','controller' => 'tasks'));
	Router::connect('/milestones', array('plugin'=>'todo','controller' => 'milestones'));
	Router::connect('/releases', array('plugin'=>'todo','controller' => 'releases'));
	Router::connect('/modules', array('plugin'=>'todo','controller' => 'modules'));

	Configure::write("layout","Todo.default");

} else if (HostInfo::hostname() == 'portal') { 

} else if (HostInfo::hostname() == 'support') { 

	Router::connect('/', array('plugin'=>'support','controller' => 'questions'));
	Router::connect('/forum', array('plugin'=>'forum','controller' => 'discussions'));
	Router::connect('/questions', array('plugin'=>'support','controller' => 'questions'));
	Router::connect('/tickets', array('plugin'=>'support','controller' => 'tickets'));
	Router::connect('/tutorials', array('plugin'=>'support','controller' => 'tutorials','action'=>'index'));
	Router::connect('/tutorials/*', array('plugin'=>'support','controller' => 'tutorials','action'=>'view'));
	Configure::write("layout","Support.default");

} else if (HostInfo::hostname() == 'blog') { 
	Router::connect('/', array('plugin'=>'blog','controller' => 'posts', 'action' => 'index'));
	Router::connect('/manager', array('manager'=>1,'plugin'=>'blog','controller' => 'posts', 'action' => 'index'));
	Router::connect('/blog/post/*', array('plugin'=>'blog','controller' => 'posts', 'action' => 'view'));
	Router::connect('/post/*', array('plugin'=>'blog','controller' => 'posts', 'action' => 'view'));
	Configure::write("layout","www");
	Configure::write("blog",true);
	Configure::write("favicon", "/www/images/logo.ico");
	Configure::write("rss", "/blog/posts/index.rss");
	Router::connect('/about', array('plugin'=>'www','controller' => 'static', 'action' => 'view','about'));
	Router::connect('/contact', array('plugin'=>'www','controller' => 'static', 'action' => 'view','contact'));

} else if (HostInfo::hostname() == 'stats') { 
	Router::connect('/', array('plugin'=>'www','controller' => 'stats', 'action' => 'index'));
	
#} else if (HostInfo::hostname() == 'rescue') { 

} else { # In a site.
	Configure::write("multisite",true); # REQUIRED
	Configure::write("layout","default");

	Router::connect('/', array('controller' => 'homepages', 'action' => 'view'));
	Router::connect('/manager', array('manager'=>1,'controller' => 'sites','action'=>'view'));
	Router::connect('/admin', array('admin'=>1,'controller' => 'sites','action'=>'view'));
	Router::connect('/admin/billing', array('admin'=>1,'plugin'=>'stripe','controller' => 'stripe_billing','action'=>'view'));


	# RESCUE SITES
	Configure::write("trial_days", 30);
	#Configure::write("trial_days", 14);
	Configure::write("layout","Rescue.rescue");
	Configure::write("theme","Rescue"); # This will be unnecessary once actual view files exist  within plugin
}

if(HostInfo::hostname() == 'rescue')
{
	Router::connect('/mockup/goto/:action', array('controller' => 'mockup'));
	Router::connect('/mockup/*', array('controller' => 'mockup', 'action' => 'view'));

}


Router::connect('/login', array('user'=>1,'controller' => 'users','action'=>'login'));

Router::connect('/members', array('members'=>1,'plugin'=>'members','controller' => 'member_pages', 'action' => 'view'));
Router::connect('/members/:controller', array('members'=>1));
Router::connect('/members/:controller/:action', array('members'=>1));
Router::connect('/members/:controller/:action/*', array('members'=>1));

# PROJECT URL PARSING.... prefix. FIRST BEFORE OTHER NON-PROJECT URLS SO caught properly
$prefixes = Configure::read("Routing.prefixes");

$controllers = array(
	'news'=>'news_posts',
	'events',
	'pages',
);
$view_singular = array('pages','events'); # 

Router::connect('/project/:project_id', array('controller' => 'projects', 'action' => 'view'), array('project_id'=>'\d+','pass'=>array('project_id')));
foreach($prefixes as $prefix)
{
	Router::connect("/$prefix/project/:project_id/:action", array($prefix=>1,'controller' => 'projects'), array('project_id'=>'\d+','pass'=>array('project_id')));
}

foreach($controllers as $url=>$cont)
{
	if(is_numeric($url)) { $url = $cont; } # Same

	Router::connect("/project/:project_id/$url", array('controller' => $cont, 'action' => 'index'), array('project_id'=>'\d+'));
	if(in_array($cont, $view_singular)) { $url = Inflector::singularize($url); } # event, page, ...
	Router::connect("/project/:project_id/$url/*", array('controller' => $cont, 'action' => 'view'), array('project_id'=>'\d+'));

	# Edit/Admin links
	foreach($prefixes as $prefix)
	{
		Router::connect("/$prefix/project/:project_id/$url/:action", array($prefix=>1,'controller' => $cont), array('project_id'=>'\d+'));
		Router::connect("/$prefix/project/:project_id/$url/:action/*", array($prefix=>1,'controller' => $cont), array('project_id'=>'\d+')); # edit/delete with ID
	}
}

Router::connect("/project/:project_id/links", array('controller' => 'link_pages', 'action' => 'view'), array('project_id'=>'\d+'));
Router::connect("/project/:project_id/links/:action", array('controller' => 'links'), array('project_id'=>'\d+'));
Router::connect("/project/:project_id/downloads", array('controller' => 'download_pages', 'action' => 'view'), array('project_id'=>'\d+'));
Router::connect("/project/:project_id/downloads/:action", array('controller' => 'downloads'), array('project_id'=>'\d+'));

# Photos are a bit more complicated
Router::connect('/project/:project_id/photos', array('controller' => 'photo_albums', 'action' => 'index'), array('project_id'=>'\d+','pass'=>array('project_id')));
Router::connect('/project/:project_id/photos/album/:photo_album_id/photo/*', array('controller' => 'photos', 'action' => 'view'),array('project_id'=>'\d+','photo_album_id'=>'\d+','pass'=>array('project_id')));
Router::connect('/project/:project_id/photos/album/*', array('controller' => 'photo_albums', 'action' => 'view'),array('project_id'=>'\d+','pass'=>array('project_id')));#,array('id'=>'\d+','pass'=>array('id')));
Router::connect('/project/:project_id/photos/*', array('controller' => 'photos'), array('project_id'=>'[0-9]+','pass'=>array('project_id')));
foreach($prefixes as $prefix)
{
	Router::connect("/$prefix/project/:project_id/links/:action", array($prefix=>1,'controller' => 'links'), array('project_id'=>'\d+'));
	Router::connect("/$prefix/project/:project_id/links/:action/*", array($prefix=>1,'controller' => 'links'), array('project_id'=>'\d+')); # edit/delete with ID

	Router::connect("/$prefix/project/:project_id/photos/album/:action", array($prefix=>1,'controller' => 'photo_albums'), array('project_id'=>'\d+'));
	Router::connect("/$prefix/project/:project_id/photos/:action", array($prefix=>1,'controller' => 'photos'), array('project_id'=>'\d+'));
}

Router::connect('/project/:project_id/links', array('controller' => 'link_pages', 'action' => 'view'),array('project_id'=>'\d+'));
Router::connect('/project/:project_id/downloads', array('controller' => 'download_pages', 'action' => 'view'),array('project_id'=>'\d+'));
Router::connect('/project/:project_id/links/*', array('controller' => 'links'),array('project_id'=>'\d+'));
Router::connect('/project/:project_id/downloads/*', array('controller' => 'downloads'),array('project_id'=>'\d+'));
foreach($prefixes as $prefix)
{
	Router::connect("/$prefix/project/:project_id/links/:action", array('controller' => 'links'),array('project_id'=>'\d+','pass'=>array('project_id')));
	Router::connect("/$prefix/project/:project_id/downloads/:action", array('controller' => 'downloads'),array('project_id'=>'\d+','pass'=>array('project_id')));
}

###########################################
# Basic routes, not in project - must come after project declaration!

Router::connect('/pages', array('controller' => 'pages', 'action' => 'index'));
Router::connect('/page/*', array('controller' => 'pages', 'action' => 'view'));

Router::connect('/about', array('controller' => 'about_pages', 'action' => 'view'));
Router::connect('/contact', array('controller' => 'contact_pages', 'action' => 'view'));
Router::connect('/about/:action', array('controller' => 'about_pages'));
Router::connect('/contact/:action', array('controller' => 'contact_pages'));

Router::connect('/resources', array('controller' => 'resource_pages', 'action' => 'view'));
Router::connect('/links', array('controller' => 'link_pages', 'action' => 'view'));
Router::connect('/downloads', array('controller' => 'download_pages', 'action' => 'view'));

Router::connect('/photos', array('controller' => 'photo_albums', 'action' => 'index'));
Router::connect('/photos/album/:photo_album_id/photo/*', array('controller' => 'photos', 'action' => 'view'),array('photo_album_id'=>'\d+'));
Router::connect('/photos/album/*', array('controller' => 'photo_albums', 'action' => 'view'));#,array('id'=>'\d+','pass'=>array('id')));

Router::connect('/videos', array('plugin'=>'videos','controller' => 'videos', 'action' => 'index'));
Router::connect('/videos/:action', array('plugin'=>'videos','controller' => 'videos'));

Router::connect('/news', array('controller' => 'news_posts', 'action' => 'index'));
Router::connect('/news/*', array('controller' => 'news_posts', 'action' => 'view'));#,array('idurl'=>'[0-9a-zA-Z-]+','pass'=>array('idurl')));
Router::connect('/event/*', array('controller' => 'events', 'action' => 'view'));#,array('idurl'=>'[0-9a-zA-Z-]+','pass'=>array('idurl')));

Router::connect('/admin/dns', array('admin'=>1,'plugin'=>'dns','controller' => 'dns', 'action' => 'view'));


############################################

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
