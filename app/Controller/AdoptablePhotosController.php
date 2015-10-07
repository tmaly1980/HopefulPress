<?
App::uses("PhotosController", "Controller");

class AdoptablePhotosController extends PhotosController
{
	var $uses = array('AdoptablePhoto');

	function upload($adoptable_id=null)
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
