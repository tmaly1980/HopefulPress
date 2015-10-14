<?
class FosterForm extends AppModel
{
	var $actsAs  = array(
		'Singleton.Singleton'=>'rescue_id',
	);

	var $default_title = 'Foster Application';

	var $default_introduction = 'Please fill out the following form to the best of your abilities. We will get back to you as soon as possible.';

}
