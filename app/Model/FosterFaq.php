<?
class FosterFaq extends AppModel
{
	var $actsAs  = array(
		'Sortable.Sortable'
	);
	var $order = 'FosterFaq.ix IS NULL, FosterFaq.ix ASC';

	var $belongsTo = array(
	);
	
	var $hasMany = array(
	);
}
