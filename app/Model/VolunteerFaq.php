<?
class VolunteerFaq extends AppModel
{
	var $actsAs  = array(
		'Sortable.Sortable'
	);
	var $order = 'VolunteerFaq.ix IS NULL, VolunteerFaq.ix ASC';

	var $belongsTo = array(
	);
	
	var $hasMany = array(
	);
}
