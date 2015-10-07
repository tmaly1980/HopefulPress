<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class ContactPage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton');//,'Core.FieldDefault'); # read() will auto-create if need be

	var $minimal_fields = true;

	public $belongsTo = array(
	);

}
