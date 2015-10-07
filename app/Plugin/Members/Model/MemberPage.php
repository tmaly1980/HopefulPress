<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class MemberPage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton','Core.FieldDefault'); # read() will auto-create if need be

	public $belongsTo = array(
		'PagePhoto'=>array(
			'foreignKey'=>'page_photo_id'
		)
	);

	function default_title()
	{
		return "Members Area";
	}

	function default_description()
	{
		return "Welcome to our members only area!";
	}

}
