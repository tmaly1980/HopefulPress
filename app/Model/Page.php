<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 */
class Page extends AppModel {
	var $displayField = 'title';
	var $actsAs = array('Sluggable.Sluggable','Sortable.Sortable'); # Publishable added automatically via component

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Parent'=>array(
			'className'=>'Page',
			'foreignKey'=>'parent_id'
		),
		'PagePhoto'=>array(
			'foreignKey'=>'page_photo_id'
		)
	);
	public $hasMany = array(
		'Subpage'=>array(
			'className'=>'Page',
			'foreignKey'=>'parent_id'
		)

	);

}
