<?
class Donation extends AppModel
{
	var $order = 'Donation.created DESC';

	var $belongsTo = array(
		'Adoptable'=>array('className'=>'Rescue.Adoptable')

	);

}
