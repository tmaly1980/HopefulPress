<?
# Allow for simplified /svg/gradient, etc path naming.
Router::connect("/svg/:action", array('plugin'=>'svg','controller'=>'svg'));
Router::connect("/svg/:action/*", array('plugin'=>'svg','controller'=>'svg'));
