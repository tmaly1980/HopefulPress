<?php
App::uses("HpCommentAppModel", "HpComments.Model");

class HpComment extends HpCommentAppModel {
	var $name = 'HpComment';
	var $displayField = 'title';
	#var $auto_user_id = 'save'; // But not find()

	var $belongsTo = array(
		'User'=>array(
			'className'=>'User',
			'foreignKey'=>'user_id'
		),
		/*
		'Site'=>array(
			'className'=>'Site',
			'foreignKey'=>'site_id'
		)
		*/

	);

}
