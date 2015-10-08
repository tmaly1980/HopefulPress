<?
#App::uses("PhotosController", "Controller");
App::uses("PagePhotosController", "PagePhotos.Controller");

# FIX to use dual-purpose....
# PagePhoto style in main photo
# But also photo_album style upload, per PhotosController
# ajax container upload passed to 'upload'

# XXX WE NEED DISTINCT METHODS
# uploading is fine, but now for page_photo upload, we also need access to crop, etc methods...
#
# Rendering of pic is what we need.... lets hope it's the same function calls.

# We need to implement PagePhoto (crop, upload, edit, image/$id/wh, fullimage, thumb, delete/PARENT/$id, etc)
# ALSO behave as album/list style uploader

class AdoptablePhotosController extends PagePhotosController
{
	var $uses = array('AdoptablePhoto');

	function listupload($adoptable_id=null) # Needs unique name, so PagePhoto upload doesn't interfere....
	{
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Upload'])) # Copy over to correct place - since needs to be separate from photo list in admin_edit
			{
				$this->request->data['AdoptablePhoto'] = $this->request->data['Upload'];
			}
			if(!empty($adoptable_id))
			{
				$this->request->data['AdoptablePhoto']['adoptable_id'] = $adoptable_id;
			}
			if(!empty($this->rescue_id))
			{
				$this->request->data['AdoptablePhoto']['rescue_id'] = $this->rescue_id;
			}
			$ix = !empty($this->request->data['ix']) ? $this->request->data['ix'] : 0;
			if(!$this->AdoptablePhoto->save($this->request->data))
			{
				$this->Json->error("Cannot upload photo");
				return $this->Json->render();
			} else {
				# Encode content to append.
				$photo = $this->AdoptablePhoto->read();
				$this->Json->set("ix", $ix); # Also send back in response..
				#$this->Json->append('Photos');
				$this->Json->script("j('#AdoptablePhotos .nodata').hide();");

				# Vars for view file
				$this->set("photo", $photo['AdoptablePhoto']);
				$this->set("ix", $ix); # For rendering....
				return $this->Json->render("item");
			}
		}
		return $this->Json->render();
	}
}
