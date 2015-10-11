<?
# RESCUE STUFF
Router::connect('/pages', array('plugin'=>'rescue','controller' => 'adoption_page_overviews', 'action' => 'view'));

Router::connect('/education', array('plugin'=>'rescue','controller' => 'education_indexes', 'action' => 'view'));
Router::connect('/education/*', array('plugin'=>'rescue','controller' => 'education_pages', 'action' => 'view'));

Router::connect('/volunteer', array('plugin'=>'rescue','controller' => 'volunteer_overviews', 'action' => 'view'));
#Router::connect('/volunteer/apply', array('plugin'=>'rescue','controller' => 'volunteers', 'action' => 'add'));
#Router::connect('/volunteer/*', array('plugin'=>'rescue','controller' => 'volunteer_pages', 'action' => 'view'));

Router::connect('/donate', array('plugin'=>'donation','controller' => 'donation_pages', 'action' => 'view'));

Router::connect('/foster', array('plugin'=>'rescue','controller' => 'foster_overviews', 'action' => 'view'));
Router::connect('/foster/apply', array('plugin'=>'rescue','controller' => 'fosters', 'action' => 'add'));
Router::connect('/foster/*', array('plugin'=>'rescue','controller' => 'foster_pages', 'action' => 'view'));

Router::connect('/adoption', array('plugin'=>'rescue','controller' => 'adoption_overviews', 'action' => 'view'));
Router::connect('/adoption/apply', array('plugin'=>'rescue','controller' => 'adoptions', 'action' => 'add'));
Router::connect('/adoption/stories', array('plugin'=>'rescue','controller' => 'adoption_stories', 'action' => 'index'));
Router::connect('/adoption/adoptables', array('plugin'=>'rescue','controller' => 'adoptables', 'action' => 'index'));
Router::connect('/adoption/adoptables/:action', array('plugin'=>'rescue','controller' => 'adoptables'));
Router::connect('/adoption/adoptables/:action/*', array('plugin'=>'rescue','controller' => 'adoptables'));
Router::connect('/adoption/page/*', array('plugin'=>'rescue','controller' => 'adoption_pages', 'action' => 'view'));
Router::connect('/user/adoption/search', array('user'=>1,'plugin'=>'rescue','controller' => 'adoptables', 'action' => 'index'));


Router::connect('/adoption/form', array('plugin'=>'rescue','controller' => 'adoptions', 'action' => 'add'));

Router::connect('/user/adoption/requests', array('user'=>1,'plugin'=>'rescue','controller' => 'adoptions', 'action' => 'index'));

# Provide sanctuary style links..
Router::connect('/sanctuary', array('plugin'=>'rescue','controller' => 'adoption_overviews', 'action' => 'view'));
Router::connect('/sanctuary/animals', array('plugin'=>'rescue','controller' => 'adoptables', 'action' => 'index'));
Router::connect('/sanctuary/animals/:action', array('plugin'=>'rescue','controller' => 'adoptables'));
Router::connect('/sanctuary/animals/:action/*', array('plugin'=>'rescue','controller' => 'adoptables'));
Router::connect('/sanctuary/page/*', array('plugin'=>'rescue','controller' => 'adoption_pages', 'action' => 'view'));
Router::connect('/user/sanctuary', array('user'=>1,'plugin'=>'rescue','controller' => 'adoptables', 'action' => 'index'));
