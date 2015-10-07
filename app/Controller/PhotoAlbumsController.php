<?php
/*
Better photo albums:
- "Add album" goes to page where title, intro are editable, and 
- NORMAL add button prompts for pictures.
- ALL pictures should have caption edit button (inline edit)
- Json auto save photo album once put title in - add album_id to page, assign to pictures, json save pictures
- Later, use inline edit to change album title, introduction
- per photo controls - rotate left/right, edit caption, delete photo
- auto sortable photos as they are added



*/
App::uses('AppController', 'Controller');
class PhotoAlbumsController extends AppController {
	var $uses = array('PhotoAlbum','Photo');

/* Actions */

	public function index() {
		$this->Tracker->track();
		$this->PhotoAlbum->recursive = 1;
		$this->set('photoAlbums', $albums = $this->paginate());
	}

	public function view($id = null) {
		$this->Tracker->track();
		$this->PhotoAlbum->recursive = 1;
	 	if (!$this->PhotoAlbum->count($id)) { return $this->invalid(); }
		$this->set('photoAlbum', $album = $this->PhotoAlbum->read(null, $id));
	}

/* 	public function edit($id = null) {
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery posts add record even if nothing sent.
			if ($this->PhotoAlbum->save($this->request->data)) {
				$this->setFlash(__('The photo album has been saved'),array('action'=>'index'));
			} else {
				$this->setFlash(__('The photo album could not be saved. Please, try again.'));
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->PhotoAlbum->read(null, $id);
		}
	}

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->check($id);
		if ($this->PhotoAlbum->delete()) {
			$this->setFlash(__('Photo album deleted'), array('action'=>'index'));
		}
		$this->Session->setFlash(__('Photo album was not deleted'),array('action'=>'index'));
	}
*/ 
/* Actions */

	function stick($id)
	{
		$this->PhotoAlbum->stick($id);
		$this->setFlash("This album has been 'pinned' to the top of the list.", array('action'=>'view',$id));
	}
	function unstick($id)
	{
		$this->PhotoAlbum->unstick($id);
		$this->setFlash("This album has been 'unpinned' from the top of the list and will show normally.", array('action'=>'view',$id));
	}

	public function delete($id = null) {
		$this->check($id);
		if ($this->PhotoAlbum->delete()) {
			$this->setSuccess("Photo album deleted", array('action'=>'index'));
		} else {
			$this->setSuccess("Unable to delete photo album: ".$this->PhotoAlbum->errorString());
		}
	}

	function upload($album_id = null)
	{ # JSONY
		# Process upload
		# Assumes album_id set in form if not passed.
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Upload'])) # Copy over to correct place - since needs to be separate from photo list in admin_edit
			{
				$this->request->data['Photo'] = $this->request->data['Upload'];
			}
			error_log("UPLOADING, DATA (looking for 'ix' as form key prefix)=".print_r($this->request->data,true));
			if(!empty($album_id))
			{
				$this->request->data['Photo']['photo_album_id'] = $album_id;
				$project_id = $this->PhotoAlbum->field("project_id", array('PhotoAlbum.id'=>$album_id));
				if(!empty($project_id))
				{
					$this->request->data['Photo']['project_id'] = $project_id;
				}
			}
			$ix = !empty($this->request->data['ix']) ? $this->request->data['ix'] : 0;
			error_log("IX=$ix");
			if(!$this->Photo->save($this->request->data))
			{
				$this->Json->error("Cannot upload photo");
				return $this->Json->render();
			} else {
				# Encode content to append.
				$photo = $this->Photo->read();
				$this->Json->set("ix", $ix); # Also send back in response..
				#$this->Json->append('Photos');
				$this->Json->script("j('#Photos .nodata').hide();");

				# Vars for view file
				$this->set("photo", $photo['Photo']);
				$this->set("ix", $ix); # For rendering....
				return $this->Json->render("../PhotoAlbums/view_photo");
			}
		}
		return $this->Json->render();
	}

	# No more separate edit(), changes done inline on view()
	public function add()
	{
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery posts add record even if nothing sent.
			error_log("SAVING=".print_r($this->request->data,true));
			# Fix project_id
			if(!empty($this->request->data['Photo']) && !empty($this->request->data['PhotoAlbum']['project_id']))
			{
				$pid = $this->request->data['PhotoAlbum']['project_id'];
				foreach($this->request->data['Photo'] as &$photo)
				{
					$photo['project_id'] = $pid;
				}
			}
			if ($this->PhotoAlbum->saveAll($this->request->data)) { # Will inject photo_album_id if needed.
				return $this->setSuccess('The photo album has been saved', array('action'=>'view',$this->PhotoAlbum->id));
				error_log("VALIDATION ERROR=".print_r($this->PhotoAlbum->validationErrors,true));
			}
			return $this->setError('The photo album could not be saved: '.$this->PhotoAlbum->errorString());
		}
	}

}
