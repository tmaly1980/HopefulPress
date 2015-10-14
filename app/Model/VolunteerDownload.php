<?
class VolunteerDownload extends AppModel
{
	var $actsAs  = array(
		#'Core.Singleton'
		'Sortable.Sortable'=>'rescue_id',
		'Core.Upload'
	);
	var $order = 'VolunteerDownload.ix IS NULL, VolunteerDownload.ix ASC, VolunteerDownload.modified DESC, VolunteerDownload.created DESC';

}
