<?
Router::connect('/admin/billing', array('admin'=>1,'plugin'=>'stripe','controller'=>'stripe_billing','action'=>'view'));

