<?php
App::uses('AppModel', 'Model');
/**
 * PhotoAlbum Model
 *
 * @property Site $Site
 * @property User $User
 * @property PagePhoto $PagePhoto
 * @property Photo $Photo
 */
class PhotoAlbum extends AppModel {
	var $order = "PhotoAlbum.modified DESC"; # Updated albums (w/new photos) show back at the top of the list.

	var $actsAs = array(
		#'Projectable',
		#'Sticky',
		#'SoftDeletable',
		#'Updatable',
	'Sluggable.Sluggable'
	);


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		#'MemberPages.Member',
		#'User'
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Photo' => array(
			'className' => 'Photo',
			'foreignKey' => 'photo_album_id',
			'dependent' => false,
			'conditions' => 'Photo.filename IS NOT NULL', # Hide broken pics.
			'fields' => '',
			'order' => 'ix IS NOT NULL, ix ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	var $default_title = 'Untitled Album';

	/*
	function beforeFind($query)
	{
		if(in_array($this->findQueryType, array('first'))) { return $query; } # Ignore direct lookups.
		if(Configure::read("in_admin") && $this->findQueryType != 'recent') { return $query; }

		if(empty($query['conditions'])) { $query['conditions'] = array(); }
		else if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); }

		# Filter out empty albums on public site.
		$valid_albums = array();
                $this->Photo->recursive = -1;
                $site_id = Configure::read("site_id");
                $photos = $this->Photo->find('all',array('conditions'=>array('Photo.site_id'=>$site_id), 'fields'=>array('DISTINCT photo_album_id'),'callbacks'=>false)); # Be efficient.
                $valid_albums = Set::extract("/Photo/photo_album_id", $photos);

                # Filter albums only with photos.
                $query['conditions']["{$this->alias}.{$this->primaryKey}"] = $valid_albums;

		return $query;
	}
	*/

}
