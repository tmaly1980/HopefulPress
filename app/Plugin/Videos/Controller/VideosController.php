<?php
App::uses('AppController', 'Controller');
class VideosController extends AppController {

/* Actions */
	#var $components = array('Sortable');
	var $uses = array('Videos.Video');#,'VideoCategory');
	var $helpers = array('Videos.YouTube');

	function beforeFilter()
	{
		parent::beforeFilter();
		Configure::load("Videos.VideoConfig");
	}

	function view($id = null)
	{
	 	if (!$this->{$this->modelClass}->count($id)) { return $this->invalid(); }
		$video = $this->{$this->modelClass}->read(null, $id);
		$this->set('video', $video);
	}

	function user_view($id= null)
	{
		$this->view($id);
	}

	function user_details($id = null)
	{
		$this->view = 'user_edit'; # So always have url field, esp if video wrong, change mind ,etc

		error_log("DETAISL=".print_r($this->data,true));
		# BETTER to get whole details all at once, pass raw url and get pure data.

		# handle scenarios:
		# url submitted/changed, want preview
		# details found/edited, want to save (handled by admin_edit)
		# url changed from original, want to preview a new one again
		# 

		if(!empty($this->data[$this->modelClass]['video_url']))
		{
			if($video = $this->{$this->modelClass}->details($this->data, $id))
			{
				$this->request->data = $video;
				$this->set("video", $video);
			} else {
				$this->setFlash("Sorry, we're unable to find a valid video at that address.");
			}

			error_log("RENDERING VIDEO=".print_r($video,true));


			$this->log("RENDERING");

			#return $this->Json->render();
		}

	}

	function user_get_video_info()
	# ?????
	{
		return; # NOT YET.... getting title and description from API...for autofill.
		#
		if(!empty($this->request->query['video_url']))
		{
			$video_url = $this->request->query['video_url'];
			$video_location = $this->{$this->modelClass}->get_video_location($url);
			$video_info = $this->{$this->modelClass}->get_video_info($url);
		}
	}

	function embed($small = 0)
	{
		if(!empty($this->params['form']['video_url']))
		{
			$video_url = $this->params['form']['video_url'];
			$video_location = $this->{$this->modelClass}->get_video_location($video_url);
			if(!empty($video_location))
			{
				$this->set("video_site", $video_location[0]);
				$this->set("video_id", $video_location[1]);
				if ($video_location[0] == 'youtube.com')
				{
					# See if we can get video info....
					#$videoData = $this->YouTubeVideo->read(null, $video_location[1]);
					#$this->set("videoData", $videoData);
					#echo "VD ($video_location)=".  print_r($videoData,true);
					# DOesnt quite work.... oh well... not sure why.....
				}
			}
		}
		$this->set("small", $small);
	}

	function leftnav() { return $this->leftnav_media(); }

	public function index() {
		$this->Tracker->track();
	 	#if (!$this->{$this->modelClass}->count()) { return $this->noContent(); }

		$this->set('videos', $this->{$this->modelClass}->findAll(array('video_category_id'=>null)));
		#$this->set('video_categories', $this->{$this->modelClass}->VideoCategory->findAll());
		#$this->set("videoPage", $this->VideoPage->first());
	}

	function video($id = null)
	{
		$this->Tracker->track();
		if(!$this->{$this->modelClass}->echoFileContent($id))
		{
			return $this->invalid();
		}
	}

	public function user_index() {
	 	#if (!$this->{$this->modelClass}->count()) { return $this->noContent(); }
		$this->set('videos', $this->{$this->modelClass}->findAll(array('video_category_id'=>null)));
		#$this->set('video_categories', $this->{$this->modelClass}->VideoCategory->findAll());
		$this->set("videoCount", $this->{$this->modelClass}->findCount()); # For sorting needs.
		#$this->set("videoPage", $this->VideoPage->first());
	}

	public function user_edit($id = null) {
		if($this->_edit($id)) # Success, should redirect.
		{
			if(!empty($id))
			{
				return $this->redirect(!empty($pid)?array('plugin'=>null,'controller'=>'projects','view',$pid):array('action'=>'view',$id));
			} else {
				return $this->redirect(!empty($pid)?array('plugin'=>null,'controller'=>'projects','view',$pid):array('action'=>'index'));
			}
		}  # Else,error or edit form
	}

	function _edit($id=null)
	{
		if (!empty($this->request->data))
		{
			error_log("SAVING DOWNLOAD=".print_r($this->request->data,true));
			# If category is empty/blank, remove...
			if(isset($this->request->data['VideoCategory']['name']) && empty($this->request->data['VideoCategory']['name']))
			{
				error_log("ERASING CATEGORY");
				unset($this->request->data['VideoCategory']);
			}

			$pid = !empty($this->request->data[$this->modelClass]['project_id']) ? $this->request->data[$this->modelClass]['project_id'] : null;

			if ($this->{$this->modelClass}->saveAll($this->request->data)) {
				$this->setSuccess($id?"The video has been updated":"The video has been added");
				return true;
			} else {
				$this->setError("The video could not be saved. Please, try again.");
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
		#$videoCategories = $this->{$this->modelClass}->VideoCategory->find('list');
		#$this->set(compact('videoCategories'));
		return false;
	}

	public function user_delete($id = null) {
		/*if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}*/
		$this->check($id);
		if ($this->{$this->modelClass}->delete()) {
		# XXX does 'cascading' delete remove the category if there are no other videos using?
			$this->setFlash(__('Video deleted'), array('action'=>'index'));
		}
		$this->Session->setFlash(__('Video was not deleted'),array('action'=>'index'));
	}

}
