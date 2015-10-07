<?php

App::uses('AppController', 'Controller');

class BlogAppController extends AppController {
	public $multisite = false;

	public $helpers = array('Facebook.Facebook');

	public $theme = 'www';
	public $layout = 'www';

}

