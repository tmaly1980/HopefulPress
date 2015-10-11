<?
class VolunteerPageDownload extends AppModel
{
	var $actsAs  = array(
		#'Core.Singleton'
		'Sortable.Sortable',
		'Core.Upload'
	);
	var $order = 'VolunteerPageDownload.ix IS NULL, VolunteerPageDownload.ix ASC, VolunteerPageDownload.modified DESC, VolunteerPageDownload.created DESC';

}
