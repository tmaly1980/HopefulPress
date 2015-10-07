<?
class Download extends AppModel
{ 
	var $actsAs = array('Sortable.Sortable','Core.Upload'); 
	var $order = 'Download.modified DESC, Download.created DESC';
	var $belongsTo = array(
	);

}
