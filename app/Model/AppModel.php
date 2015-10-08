<?php

App::uses('CoreAppModel', 'Core.Model');

class AppModel extends CoreAppModel {
	var $actsAs = array('ValidationMessage',
		'AutoId'=>array( # Might be rescue_id, etc.
			'fields'=>array('rescue_id'), # More???
		),
		'UserCore.Autouser',
		#'Project.Projectable','Members.MembersOnly','Chart.Chartable',
		'Core.FieldDefault');
	var $autouser = true;

}
