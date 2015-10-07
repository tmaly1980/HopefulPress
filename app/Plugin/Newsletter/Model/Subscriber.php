<?php
App::uses("NewsletterAppModel", "Newsletter.Model");
class Subscriber extends NewsletterAppModel {

	function beforeSave($options = array())
	{
		# If new, set confirm_code

		if(empty($this->id) && empty($this->data[$this->alias]['confirm_code']))
		{
			$this->data[$this->alias]['confirm_code'] = $this->random_chars(16);
		}

		return true;
	}
}
