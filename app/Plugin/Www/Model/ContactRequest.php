<?
App::uses("WwwAppModel", "Www.Model");
class ContactRequest extends WwwAppModel
{
	var $actsAs = array(
	);

	var $order = 'ContactRequest.created DESC';

	var $belongsTo = array(
		'Site',
		'User'
	);

}
