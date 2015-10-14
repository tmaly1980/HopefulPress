<?php
App::uses('UserCore', 'UserCore.Model');

class User extends UserCore {
	var $bypassSiteField = 'manager'; # ignore site_id when querying... ie for login or update account of managers (inter-site users)
	# Works for find() but not save() - since flag is probably not passed.
	# Better to pass autosite=>false when wanted to bypass save() injecting site_id

	var $autoid = false; # Don't force rescue_id to match when searching/logging in/etc.

	var $displayField = "name_email";

	var $virtualFields = array(
		"name"=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)",
		"full_name"=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)",
		"name_email"=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name, ' (', %ALIAS%.email, ')')"
	);

	var $hasMany = array(
		'RescueVolunteer'=>array('foreignKey'=>'user_id'),
		'RescueFoster'=>array('foreignKey'=>'user_id'),
	);

	var $belongsTo = array(
		'Rescue', # Possibly
		# Maybe turn into hasOne???or hasMany?
		'PagePhoto'=>array(
			'className'=>'PagePhotos.PagePhoto'
		),
	);
	
}
