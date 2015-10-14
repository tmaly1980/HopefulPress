<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class ResourcePage extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Singleton.Singleton'=>'rescue_id');#,'Project.Projectable');//,'Core.FieldDefault'); # read() will auto-create if need be

	public $hasMany = array(
	);

}
