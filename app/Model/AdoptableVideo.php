<?
App::uses("Video", "Videos.Model");

class AdoptableVideo extends Video
{
	var $order = 'AdoptableVideo.id DESC';
	var $belongsTo = array(
		'Adoptable'=>array(
			'className'=>'Adoptable',
		)
	);

}
