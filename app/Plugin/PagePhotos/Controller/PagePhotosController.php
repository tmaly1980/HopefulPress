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

	function vars($pluginModelClass=null)
	{
		Configure::load("pagePhoto");
		$vars = Configure::read("PagePhoto.$pluginModelClass");

		# Load
		$defaults = array(
			'primaryKey'=>Inflector::underscore($this->modelClass)."_id",
			'plugin'=>(!empty($this->request->params['plugin']) ? "/{$this->request->params['plugin']}" : ""),
			'controller'=>$this->request->params['controller'],
		);

		return is_array($vars)  ? array_merge($defaults, $vars) : $defaults;
	}

	function json_edit($id = null) # Show inline edit widget.
	{
		# This seems wrong for alternative logos,etc...
		extract($this->vars());

		if(!empty($id))
		{
			$photo = $this->{$this->modelClass}->read(null, $id);
			error_log("PHOTO=".print_r($photo,true));
			$this->set("page_photo_id", $id);
			if(!empty($photo[$this->modelClass]['width'])) { $this->set("width", $photo[$this->modelClass]['width']); }
			if(!empty($photo[$this->modelClass]['height'])) { $this->set("height", $photo[$this->modelClass]['height']); }
			$this->Json->set("src", "$plugin/$controller/image/$id"); # 300x300
			$this->Json->set("filelink", "$plugin/$controller/image/$id"); # redactor
			$this->request->data = $photo; # For Model->field()
			#$this->Json->set("thumb_src", "/page_photos/thumb/".$this->PagePhoto->id); # Unused for now... since above is so small anyway.
		}
		$this->Json->replace($this->modelClass);
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

	function delete($model, $model_id = null)
	{
		extract($this->vars($model));

		$pluginModelClass = $model;
		list($p,$model) = pluginSplit($model);

		$this->set("modelClass", $pluginModelClass);
		$this->set("model_id", $model_id);

		$this->loadModel($pluginModelClass);
		if(empty($model_id))
		{
			$model_id = $this->{$model}->first_id();
		}
		$page = $this->{$model}->read(null, $model_id);
		if(!empty($page))
		{
			$page_photo_id = $page[$model][$primaryKey];
			if(!empty($page_photo_id))
			{
				$this->{$model}->saveField($primaryKey, null);
				$this->{$this->modelClass}->delete($page_photo_id);
			}
		}
		$this->render("PagePhotos.../Elements/edit");
	}

	function upload($model = null, $model_id = null)
	{
		return $this->edit($model,$model_id);
	}
	function crop($pluginModelClass = null, $model_id = null)
	{
		extract($this->vars($pluginModelClass));

		error_log("CROP CALLED");
		$this->edit($pluginModelClass,$model_id,true); # Process first.
		error_log("CROP CONTINUED, DONE WITH EDIT");

		list($p,$modelClass) = pluginSplit($pluginModelClass); # Dont'  screww up plugin for controller url vars
		$this->loadModel($pluginModelClass);

		$this->{$modelClass}->id = $model_id;
		$page_photo_id = $this->{$modelClass}->field($primaryKey);
		$this->request->data = $this->{$this->modelClass}->read(null, $page_photo_id);
	}

	function edit($pluginModelClass = null, $model_id = null, $incrop = false) 
	{ # Model passed is parent object. We know who we are.

		extract($this->vars($pluginModelClass));

		if(empty($pluginModelClass)) { $pluginModelClass = $this->uses[0]; }
		if($pluginModelClass == 'PagePhoto') { $pluginModelClass = 'PagePhotos.PagePhoto'; }
		$this->set("modelClass", $pluginModelClass);  # Could belong to a plugin
		$pluginModelClassId = Inflector::underscore(Inflector::singularize($pluginModelClass))."_id";
		$this->set("model_id", $model_id);
		# Make available for JSON

		list($p,$modelClass) = pluginSplit($pluginModelClass);
		$this->loadModel($pluginModelClass);

		# Existing records are never replaced, just forgotten.
		# This is bad code, saves to photo given id of page
		#if(!empty($model_id)) { $this->{$this->modelClass}->id = $model_id; }
		# *** it's the parent/page id

		# This requires we have a belongsTo set up, since form uses PagePhoto/etx

		if(!empty($this->request->data))
		{
			# If photo object has MODEL_id field, set that too.
			if(!empty($model_id) && $this->{$this->modelClass}->hasField($pluginModelClassId))
			{
				$this->request->data[$this->modelClass][$pluginModelClassId] = $model_id;

			}

			error_log("DATA=".print_r($this->request->data,true));
			# This here is the Photo class...
			if($this->{$this->modelClass}->save($this->request->data))
			{
				#$this->admin_edit($this->PagePhoto->id);
				$this->set($primaryKey, $this->{$this->modelClass}->id);

				if(!empty($modelClass) && !empty($model_id)) # ie if inline edit, about_pages, etc.
				{
					$this->loadModel($modelClass);
					$this->{$modelClass}->id = $model_id;
					$this->{$modelClass}->saveField($primaryKey, $this->{$this->modelClass}->id);
				}

				if(!empty($this->request->data['tinymce']))
				{
					// fill in with url and then close dialog successfully.
					//top.\$('.mce-btn.mce-open').parent().find('.mce-textbox').val('%s').closest('.mce-window').find('.mce-primary').click();</script>", "/page_photos/page_photos/image/$pid");
					$this->set("resultcode", "ok");
					$this->set("result", "file_uploaded");
					$this->set("file_name", "$plugin/$controller/image/".$this->PagePhoto->id);
					return $this->render("tinymce");
				} else {
					if($incrop)
					{
						return $this->json_edit($this->{$this->modelClass}->id);
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
	
	function image($id,$wh='300x300') # Cropped version.
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
		return $this->fullimage($id,$wh);
	}

	function fullimage($id,$wh='300x300') # Non-cropped version
	{ # 
		extract($this->vars());

		$whparts = split("x", $wh);
		if(empty($whparts[1])) { $whparts[1] = $whparts[0]; } # 300 => 300x300
		list($w,$h) = $whparts;
		# Less than 300x300

		$image = $this->{$this->modelClass}->read(null, $id);
		if(!empty($this->request->query['fullsize'])) { $w = null; $h = null; }

		return $this->Image->render($image[$this->modelClass], $w, $h);
	}

	# Allow size parameters to omit w or h, so we can fit by w or h
	# XXX WILL CROP
	function thumb($id,$wh="200x115",$crop = false) # landscape shape
	{
		extract($this->vars());

		# with $crop  off, it's perfect.

		$image = $this->{$this->modelClass}->read(null, $id);
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
