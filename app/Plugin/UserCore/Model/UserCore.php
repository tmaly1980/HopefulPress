<?php
App::uses('UserCoreAppModel', 'UserCore.Model');
class UserCore extends UserCoreAppModel {
	var $actsAs = array('UserCore.EncryptedPassword');

	var $invite = false; # Whether or not to create invite code on create.

	#################################
	# VALIDATION

	public $validate = array(
		'email'=>array(
			'notEmpty'=>array(
				'rule'=>'notEmpty',
				'message'=>'Email is required'
			),
			'email'=>array(
				'rule'=>'email',
				'message'=>'Email is not valid, must look like username@hostname.com'
			),
			'isUnique'=>array(
				'rule'=>'isUnique',
				'on'=>'create',
				'message'=>'There already is an account with that email'
			)
		),
		'password'=>array(
			'required'=>array(
				'rule'=>'notEmpty',
			),
			'minLength'=>array(
				'rule'=>array('minLength',8),
				'required'=>1,
			),
		),
		'password2'=>array(
			'required'=>array(
				'rule'=>'notEmpty',
			),
			'minLength'=>array(
				'rule'=>array('minLength',8),
			),
			// XXX password2 needs to know what password1 is!
			'samePass'=>array(
				'rule'=>array('passwordsSame'),
				'message'=>"Passwords do not match",
			),
		),
		'first_name'=>array(
			'rule'=>'notEmpty',
		),
		'last_name'=>array(
			'rule'=>'notEmpty',
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		/*
		'Site' => array(
			'className' => 'Sites.Site',
			'foreignKey' => 'site_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		*/
	);

/**
 * hasMany associations
 *
 * @var array
 */
 	public $virtualFields = array(
		'full_name'=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)",
	);
	public $hasMany = array(
	);

	var $passwordRequired = false; # If password needed to save.

	function beforeValidate($options = array())
	{
		$passwordField = Configure::read("User.passwordField");
		if(empty($passwordField)) { $passwordField = 'password'; }
		$passwordField2 = $passwordField.'2';

		if(isset($this->data[$this->alias][$passwordField]) && empty($this->data[$this->alias][$passwordField])) { # Ignore.
			unset($this->data[$this->alias][$passwordField]);
		}
		if(isset($this->data[$this->alias][$passwordField2]) && empty($this->data[$this->alias][$passwordField2])) { # Ignore.
			unset($this->data[$this->alias][$passwordField2]);
		}
		return parent::beforeValidate($options);
	}

	function beforeSave($options = array())
	{
		# New with no password set OR invite set.

		# If created with no password, set/create invite
		# If invite === true, create new invite

		if(!empty($this->data[$this->alias]['invite']) || !empty($this->invite) || (empty($this->id) && empty($this->data[$this->alias]['id']) && empty($this->data[$this->alias]['password']))) # Creating or re-inviting.
		{
			$this->data[$this->alias]['invite'] = $this->random_chars(16);
		}
		# TO reset invite, just set $this->User->invite = true; (OR set invite key to a value)

		return true;
	}

	function passwordsSame()#$data) # Full data is only available in $this->data[$this->alias]; NOT $data passed
	{
		$passwordField = Configure::read("User.passwordField");
		if(empty($passwordField)) { $passwordField = 'password'; }
		$passwordField2 = $passwordField."2";

		if(empty($this->data[$this->alias][$passwordField])) { return false; }
		if(empty($this->data[$this->alias][$passwordField2])) { return false; }
		if($this->data[$this->alias][$passwordField] != $this->data[$this->alias][$passwordField2]) { return false; }
		return true;
	}

	function emailList($recips) 
	# Translate list of recipients to email list.
	{ # Shared between User and Subscriber, so needed here

		$emailField = Configure::read("User.emailField");
		if(empty($emailField)) { $emailField = 'email'; }

		if(!$this->hasField($emailField)) { return $recips; }

		#print_r($recips);

		$toList = array();
		
		if(!is_array($recips))
		{
			$recips = array($recips);
		}

		foreach($recips as $recip)
		{
			$email = null;
			if(!empty($recips[$this->alias][$emailField]))  # Single user by record.
			{
				$email = $recips[$this->alias][$emailField];
			} else if (is_numeric($recip)) { #user id...
				$this->id = $recip;
				$email = $this->field($emailField);
			} else if(preg_match("/\w+@\w+/", $recip)) { # Already email
				$email = $recip;
			}

			$toList[] = $email;
		}

		error_log("EMAIL LIST=".print_r($toList,true));

		return $toList;
	}
}
