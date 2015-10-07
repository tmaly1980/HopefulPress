<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class SiteDesign extends AppModel {
	var $actsAs = array('Singleton.Singleton','Core.FieldDefault'); # read() will auto-create if need be

	var $default_theme = 'default';
	var $default_color1 = '38761D';

	function default_title() # Need to populate so we know when erased for logo-only
	{
		if($this->Site->id = Configure::read("site_id"))
		{
			return $this->Site->field('title');
		}
	}

	public $belongsTo = array(
		'SiteDesignLogo',
		'Site'
	);

}
