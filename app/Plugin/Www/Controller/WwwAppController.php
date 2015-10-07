<?php

App::uses("AppController", "Controller");
class WwwAppController extends AppController {
	public $multisite = false;

	public $site_title = 'Hopeful Press';

	#public $helpers = array('Facebook.Facebook');

	public $title = '';

	public $layout = 'www';
	public $theme = 'www'; # Should overwrite CSS
	# WTF this screws everyting up


}

