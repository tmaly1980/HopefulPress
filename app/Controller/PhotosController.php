<?php
App::uses('AppController', 'Controller');
class PhotosController extends AppController {
	var $components = array('Core.Json','Image.Image','Sortable.Sortable');
	var $uses = array('Photo','PhotoAlbum');

/* Actions */

	public function index() {
		$this->redirect(array('controller'=>'photo_albums'));
	}

	public function view($id = null) {
		$this->track();
	 	if (!$this->{$this->modelClass}->count($id)) { return $this->invalid(); }
		$photo = $this->{$this->modelClass}->read(null, $id);
		$this->set('photo', $photo);
		$album = !empty($photo[$this->modelClass]['photo_album_id']) ? $this->PhotoAlbum->read(null, $photo[$this->modelClass]['photo_album_id']) : array();
		$this->set("photoAlbum", $album);
	}

	function thumb($id, $wh="200x115", $crop = true)
	{
		$whparts = split("x", $wh);
		if(empty($whparts[1])) { $whparts[1] = $whparts[0]; } # 300 => 300x300
		list($w,$h) = $whparts;
		$image = $this->{$this->modelClass}->read(null, $id);
		return $this->Image->render($image[$this->modelClass], $w,$h,$crop);
	}

	function image($id)
	{
		$w = 600; 
		$image = $this->{$this->modelClass}->read(null, $id);
		return $this->Image->render($image[$this->modelClass], $w);
	}

	function fullimage($id)
	{
		$image = $this->{$this->modelClass}->read(null, $id);
		return $this->Image->render($image[$this->modelClass]);
	}

	function rotate($id, $dir = 0) # Rotate is relative to current setting.
	{ # in album edit...
		$image = $this->{$this->modelClass}->read(null, $id);
		$existing = !empty($image[$this->modelClass]['rotate']) ? $image[$this->modelClass]['rotate'] : 0;

		$rotate = $existing + ($dir > 0 ? 90 : ($dir < 0 ? -90 : 0));
		$rotate %= 360; // max.

		$this->{$this->modelClass}->set("rotate", $rotate);
		$this->{$this->modelClass}->save();

		# Not sure what to return....
		$rand = rand(1000,9999999);
		$this->Json->script("j('#Photo_$id img.Photo.thumb').attr('src', '/photos/thumb/$id?rand=$rand');"); # Reload
		$this->Json->render();
	}


	/*
	public function admin_index() {
		$this->redirect(array('controller'=>'photo_albums'));
	}

	public function admin_view($id = null) {
		$this->view($id);
	}

	public function admin_edit($id = null) {
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery posts add record even if nothing sent.
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setFlash(__('The photo has been saved'),array('action'=>'index'));
			} else {
				$this->setFlash(__('The photo could not be saved. Please, try again.'));
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}
		$photoAlbums = $this->{$this->modelClass}->PhotoAlbum->find('list');
		$this->set(compact('photoAlbums'));
	}
	*/

	public function delete($id = null) {
		# JSON is never a 'post'
		#if (!$this->request->is('post')) {
		#	throw new MethodNotAllowedException();
		#}
		#$this->check($id);

		$photo = $this->{$this->modelClass}->read(null, $id);
		$album_id = !empty($photo[$this->modelClass]['photo_album_id']) ? $photo[$this->modelClass]['photo_album_id'] : null;

		if (empty($photo) || $this->{$this->modelClass}->delete()) { # Still process like normal, even if doesnt exist - may be stuck on page.
			#error_log("AJAX?=".$this->request->is('ajax'));

			#if($this->request->is('ajax')) # From album edit list
			if($this->request->ext == 'json') # From album edit list
			{ # is('json') happens only with getJSON, not with post() even if .json extension (simply by accept-type */*)
				//$this->Json->script("j('#Photo_$id').remove(); j('#Photos').trigger('updated');");
				$this->Json->script("$('#{$this->modelClass}_$id').smartRemove();");
				return $this->Json->render();
			} else if(!empty($album_id)) { # From photo details page
				$this->setFlash(__('Photo deleted'), array('controller'=>'photo_albums','action'=>'view',$album_id));
			} else {
				$this->setFlash(__('Photo deleted'), array('controller'=>'photo_albums','action'=>'index'));

			}
		}
		$this->Session->setFlash(__('Photo was not deleted'),array('action'=>'index'));
	}

}
