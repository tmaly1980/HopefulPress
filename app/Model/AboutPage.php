<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class AboutPage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton');//,'Core.FieldDefault'); # read() will auto-create if need be

	var $minimal_fields = true;

	public $belongsTo = array(
		'PagePhoto'=>array(
			'foreignKey'=>'page_photo_id'
		)
	);

	/*
	function default_title()
	{
		return "About Us";
	}

	function default_introduction()
	{
		return null; #"Welcome to our organization!";
	}

	public $inlineFieldTips = array(
		'title'=>"Provide a title for the homepage",
		'introduction'=>"A good intruction summarizes the purpose of your organization and catches reader interest",
	);
	*/
}
