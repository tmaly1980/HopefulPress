<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class Homepage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton','Core.FieldDefault'); # read() will auto-create if need be

	public $belongsTo = array(
		'PagePhoto'=>array(
			'foreignKey'=>'page_photo_id'
		)
	);

	function default_title()
	{
		return "Our Mission";
	}

	function default_introduction()
	{
		return "Welcome to our rescue! Below you'll find a list of adoptables, as well as recent news, events, photos and more. Look through our resources above to learn more about adoption, fostering, and responsible pet ownership.";
	}

	public $inlineFieldTips = array(
		'title'=>"Provide a title for the homepage",
		'introduction'=>"A good intruction summarizes the purpose of your organization and catches reader interest",
	);
}
