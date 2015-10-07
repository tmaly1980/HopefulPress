<?
Router::connect('/donation', array('plugin'=>'donation','controller' => 'donation_pages', 'action' => 'view'));
Router::connect('/user/donation/oauth', array('user'=>1,'plugin'=>'donation','controller' => 'donation_pages', 'action' => 'oauth')); # Redirect to stripe
Router::connect('/donation/authorize', array('plugin'=>'donation','controller' => 'donation_pages', 'action' => 'authorize')); # Response, Link with stripe account
Router::connect('/donation/donate', array('plugin'=>'donation','controller' => 'donations', 'action' => 'donate'));
Router::connect('/donation/donate/*', array('plugin'=>'donation','controller' => 'donations', 'action' => 'donate'));
Router::connect('/donation/thanks', array('plugin'=>'donation','controller' => 'donations', 'action' => 'thanks'));
Router::connect('/donation/ipn', array('plugin'=>'donation','controller' => 'donations', 'action' => 'ipn')); # Paypal transaction notification
