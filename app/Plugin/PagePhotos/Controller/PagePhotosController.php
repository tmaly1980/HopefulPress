<?
class PagePhotosController extends AppController
{
	var $components = array('Core.Json','Image.Image','Editable.Editable');
	var $uses = array('PagePhotos.PagePhoto');

	/*
	function admin_align($id, $align = 'right')
	{
		$this->PagePhoto->id = $id;
		$this->PagePhoto->saveField("align", $align);
		$this->Json->set("status", "OK");
		$this->Json->set("align", $align);
		$this->Json->render();
	}

	function admin_view_hide($model, $id)
	{
		$this->PagePhoto->id = $id;
		$this->PagePhoto->saveField("view_hidden", 1);
		$this->set("model", $model); # Required to refresh.

		$this->json_edit($id);
	}

	function admin_view_show($model, $id)
	{
		$this->PagePhoto->id = $id;
		$this->PagePhoto->saveField("view_hidden", 0);
		$this->set("model", $model); # Required to refresh.

		$this->json_edit($id);
	}
	*/

	# WE NEED TO KNOW THE PARENT SO WE CUSTOMIZE PROPERLY
	function vars()#$pluginModelClass=null)
	{
		Configure::load("pagePhoto");#Have access
		/*
		$vars = Configure::read("PagePhoto.$pluginModelClass");
		*/

		# Load
		$defaults = array(
			'primaryKey'=>Inflector::underscore($this->modelClass)."_id",
			'plugin'=>(!empty($this->request->params['plugin']) ? "/{$this->request->params['plugin']}" : ""),
			'controller'=>$this->request->params['controller'],
		);
		return $defaults;

		#return is_array($vars)  ? array_merge($defaults, $vars) : $defaults;
	}

	function json_edit($id = null,$parentClass=null,$photoModel='PagePhoto') # Show inline edit widget.
	{
		# This seems wrong for alternative logos,etc...
		extract($this->vars());

		if(!empty($id))
		{
			$photo = $this->{$this->modelClass}->read(null, $id);
			#error_log("PHOTO=".print_r($photo,true));
			#$this->set($primaryKey, $id);
			$this->set("page_photo_id", $id); # Needs to be generic.
			if(!empty($photo[$this->modelClass]['width'])) { $this->set("width", $photo[$this->modelClass]['width']); }
			if(!empty($photo[$this->modelClass]['height'])) { $this->set("height", $photo[$this->modelClass]['height']); }
			$this->Json->set("src", "$plugin/$controller/image/$id"); # 300x300
			$this->Json->set("filelink", "$plugin/$controller/image/$id"); # redactor

			# Since might be PagePhoto and uses an alias, copy over to safe model.
			$this->request->data[$photoModel] = $photo[$this->modelClass];

			#$this->Json->set("thumb_src", "/page_photos/thumb/".$this->PagePhoto->id); # Unused for now... since above is so small anyway.
		}
		error_log("REPLACING $photoModel, PLUG=$plugin, CONT=$controller, ID=$id");
		$this->Json->replace($photoModel);
		$this->Json->render("PagePhotos.edit");
	}

	/*
	function admin_resize($id)
	{
		error_log("D=".print_r($this->request->data,true));

		if(!empty($this->request->data))
		{
			$width = $this->request->data['width'];
			$height = $this->request->data['height'];

			$this->PagePhoto->id = $id;
			$this->PagePhoto->saveField("width", $width);
			$this->PagePhoto->saveField("height", $height);
			$this->Json->set("status", "OK");
			$this->Json->set("width", $width);
			$this->Json->set("height", $height);
		} else {
			$this->Json->set("status", "NODATA");
		}
		$this->Json->render();
	}
	*/

	function delete($parentClass, $photoModel = null, $id = null) # Removes reference from parent object
	{
		extract($this->vars());#$model));

		#error_log("PPDEL=$parentClass");

		# For compatibility with Photos list/album in case double-implemented.
		if(empty($id) && is_numeric($parentClass)) # Just remove self, don't bother with parent (just de-reference belongsTo to see validity)
		{
			$id = $parentClass;
			$parentClass = null;
		}

		$this->{$this->modelClass}->delete($id); # Delete self.

		# Now remove reference from parent, if applicable.
		if(!empty($parentClass) && !empty($photoModel))
		{
			$this->loadModel($photoModel);
			if(!empty($parentClass))# Grab better primary key
			{
				$this->loadModel($parentClass);
				if(!empty($this->{$parentClass}->belongsTo[$photoModel]['primaryKey']))
				{
					$primaryKey = $this->{$parentClass}->belongsTo[$photoModel]['primaryKey'];
				} else {
					$primaryKey = Inflector::underscore($photoModel)."_id";
				}
			}

			#error_log("LOOKING FOR $parentClass WITH $primaryKey => $id");
			$parent_id = $this->{$parentClass}->field('id', array($primaryKey=>$id));
			#error_log("GOT=$parent_id");

			if(!empty($parent_id))
			{
				$this->{$parentClass}->id = $parent_id;# Must be explicit since we didn't call read() before.
				$this->{$parentClass}->saveField($primaryKey, null);
			}

			$this->set("parentClass", $parentClass);
			$this->set("photoModel", $photoModel);
			$this->set("model_id", $parent_id);
			$this->render("PagePhotos.../Elements/edit");
		} else if ($this->request->ext == 'json') { # For album list style, etc.
                	$this->Json->script("$('#{$this->modelClass}_$id').smartRemove();");
                	return $this->Json->render();
		} else { # Doesnt matter.
			return $this->redirect(array('action'=>'index')); # Best guess.
		}
	}

	function upload($parentClass,$photoModel='PagePhoto',$id=null) # Replace?
	{
		#error_log("UPLOAD");
		return $this->edit($parentClass,$photoModel,$id);
	}

	function crop($parentClass,$photoModel='PagePhoto',$id=null)
	{
		$this->set("parentClass",$parentClass); # Custom views/modals
		$this->set("photoModel",$photoModel); # Might just be an alias. If we really dont use PagePhoto, we need a custom controller w/uses set proper
		$this->set("model_id",$id);
		extract($this->vars());#$parentClass));
		$this->set($primaryKey, $id);
		$this->set("page_photo_id", $id);
		#error_log("CROP $id, PC=$parentClass, PM=$photoModel");

		#error_log("CROP CALLED");
		$this->edit($parentClass,$photoModel,$id,true); # Process first.
		#error_log("CROP CONTINUED, DONE WITH EDIT");


		$this->request->data = !empty($id) ? $this->{$this->modelClass}->read(null, $id) : array();
	}

	function edit($parentClass,$photoModel='PagePhoto',$id = null, $incrop = false) 
	{ # Model passed is parent object. We know who we are.
		$this->set("parentClass",$parentClass); # So views can be custom.
		$this->set("photoModel",$photoModel); 
		$this->set("model_id",$id);

		extract($this->vars());#$pluginModelClass));

		# Existing records are never replaced, just forgotten.
		# This is bad code, saves to photo given id of page
		if(!empty($id)) { $this->{$this->modelClass}->id = $id; }
		# *** it's the parent/page id

		# This requires we have a belongsTo set up, since form uses PagePhoto/etx

		#error_log("D=".print_r($this->request->data,true));

		if(!empty($this->request->data))
		{
			#error_log("DATA=".print_r($this->request->data,true));
			# This here is the Photo class...
			if($this->{$this->modelClass}->save($this->request->data))
			{
				#error_log("DATAFILE ".$this->{$this->modelClass}->id."=".print_r($this->{$this->modelClass}->read(),true));
			#	#error_log("SETTING $primaryKey ({$this->modelClass})=> ".$this->{$this->modelClass}->id);
				$this->set($primaryKey, $this->{$this->modelClass}->id);
				$this->set("page_photo_id", $this->{$this->modelClass}->id);
				# ie page_photo_id, for loading preview...

				# primary key name is based on model class (and thus controller)....ie page_photo_id, rescue_logo_id

				# NO MORE INLINE EDIT UPDATE PARENT ID FOREIGN KEY
				
				if(!empty($this->request->data['tinymce']))
				{
					// fill in with url and then close dialog successfully.
					//top.\$('.mce-btn.mce-open').parent().find('.mce-textbox').val('%s').closest('.mce-window').find('.mce-primary').click();</script>", "/page_photos/page_photos/image/$pid");
					$this->set("resultcode", "ok");
					$this->set("result", "file_uploaded");
					$this->set("file_name", "$plugin/$controller/image/".$this->{$this->modelClass}->id);
					return $this->render("tinymce");
				} else {
					if($incrop)
					{
						return $this->json_edit($this->{$this->modelClass}->id,$parentClass,$photoModel);
					} else {
						# Go to crop (just uploaded)
						$this->request->data = $this->{$this->modelClass}->read();
						$this->Json->update("modal");
						return $this->Json->render("crop");
					}
				}
			} else {
				if(!empty($this->request->data['tinymce']))
				{
					$this->set("result", "Could not save photo: ".$this->{$this->modelClass}->errorString());
					$this->set("resultcode", "failed");
					return $this->render("tinymce");
				} else {
					$this->Json->error("Could not save photo: ".$this->{$this->modelClass}->errorString());
					return $this->Json->render();
				}
			}
		}
		#$this->Json->error("Photo not provided");
	}



	function original($id)
	{
		extract($this->vars());

		$image = $this->{$this->modelClass}->read(null, $id);
		return $this->Image->render($image[$this->modelClass]);
	}

	function  view($id)
	{
		return $this->original($id);
	}
	
	function image($id,$wh='300x300',$crop=false) # Cropped version.
	{
		extract($this->vars());
		$image = $this->{$this->modelClass}->read(null, $id);
		if(!empty($image[$this->modelClass]['crop_w']))
		{
			$this->Image->coords(
				$image[$this->modelClass]['crop_x'],
				$image[$this->modelClass]['crop_y'],
				$image[$this->modelClass]['crop_w'],
				$image[$this->modelClass]['crop_h']
			);
		}
		return $this->fullimage($id,$wh,$crop);
	}

	function fullimage($id,$wh='300x300',$crop=false) # Non-cropped version
	{ # 
		extract($this->vars());

		$whparts = split("x", $wh);
		if(empty($whparts[1])) { $whparts[1] = $whparts[0]; } # 300 => 300x300
		list($w,$h) = $whparts;
		# Less than 300x300

		$image = $this->{$this->modelClass}->read(null, $id);
		if(!empty($this->request->query['fullsize'])) { $w = null; $h = null; }

		return $this->Image->render($image[$this->modelClass], $w, $h, $crop);
	}

	# Allow size parameters to omit w or h, so we can fit by w or h
	# XXX WILL CROP
	function thumb($id,$wh="200x115",$crop = false) # landscape shape
	{
		extract($this->vars());

		# with $crop  off, it's perfect.

		$image = $this->{$this->modelClass}->read(null, $id);
		# IF NOT FOUND, MIGHT BE WRONG SITE/RESCUE...

		$whparts = split("x", $wh);
		if(empty($whparts[1])) { $whparts[1] = $whparts[0]; } # 300 => 300x300
		list($w,$h) = $whparts;

		if(!empty($image[$this->modelClass]['crop_w']))
		{
			$this->Image->coords(
				$image[$this->modelClass]['crop_x'],
				$image[$this->modelClass]['crop_y'],
				$image[$this->modelClass]['crop_w'],
				$image[$this->modelClass]['crop_h']
			);
		}

		return $this->Image->render($image[$this->modelClass], $w,$h, $crop);
	}

	/* BUILT IN to AppCore
	function editable($field, $id = null) # For caption editing?
	{
		$this->Editable->editable($field, $id);
	}
	*/

}
