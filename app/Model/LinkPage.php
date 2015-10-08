<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class LinkPage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton');#,'Project.Projectable');//,'Core.FieldDefault'); # read() will auto-create if need be

	public $hasMany = array(
	);

}
