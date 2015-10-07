<?

# We have really custom 
class UploadBehavior extends ModelBehavior
{
	var $errors = array();
	var $uploadFolder = "uploads"; # Where all files/subdirs reside...
	var $default_settings = array(
		'dir'=>'uploads',
		'basedir'=>null,
		'field'=>'file',
		'required'=>true,
		'mime_types'=>array(),
			# List of file type restrictions, others will be invalid.
	);

	var $settings = array(); #Since only one object, must store by model key.

	# XXX TODO valid mime types/extensions...

	function setup(Model $model, $config=array())
	{
		#error_log("UPLOAD SETUP");

		#$this->model = $model;

		$this->settings[$model->alias] = array_merge($this->default_settings, $config);

		if($this->settings[$model->alias]['basedir'] === null) # Not specified, and not false.
		{
			$this->settings[$model->alias]['basedir'] = Inflector::pluralize(Inflector::tableize($model->alias));
		}
		
		# field, dir, etc.
		# dir: uploads, images, files, etc based on type
	}

	function beforeSave(Model $model, $options = array())
	{
		#error_log("UPLOAD BEFORE SAVE.... settings=".print_r($this->settings,true));

		# Need to put this stuff here so it gets the site_id in time...
		# ? what if no site available?
		# /app/webroot/uploads/23/download_files/
		# /app/webroot/uploads/12/photos/

		# If we are dealing with multiple models, etc. 
		# All the file needs to be named is Related.file, ie Logo.file, Photo.file - the linker will handle the key, ie logo_id, photo_id, etc.

		$rc = $this->upload($model); # We have to call clearBlankUpload manually because we probably cannot reset other models' data after saveAll() is invoked.
		#error_log("DONE UPLOAD CHECK = $rc (should be 0)");
		return $rc;
		# Call upload...

		# Must return FALSE if we don't want the record saved (ie no file provided)...

	}

	function clearBlankUpload($model, &$data) # Called on a related model BEFORE saveAll, that should strip itself out of $this->data before saveAll() is called.
	# Integrated into our modified version of saveAll()
	{
		$field = 'file'; # ASSUME.
		$file_meta = !empty($model->data[$model->alias][$field]) ?  $model->data[$model->alias][$field] : null;
		if(empty($file_meta['file']) && $file_meta['error'] == UPLOAD_ERR_NO_FILE) 
		{ 
			error_log("No upload provided for {$model->alias}, REMOVING");
			unset($data[$model->alias]);
		};
		
	}

	function beforeDelete(Model $model, $cascade = true)
	{
        	$model->read(null, $model->id);
                if(isset($model->data) && $model->hasField("filename")) {
			$path = $model->data[$model->name]['path'];
			$filename = $model->data[$model->name]['filename'];
			if(!empty($filename) && !empty($path))
			{
                        	$this->deleteFiles($filename, $path);
			}
                }
                return true;

	}

	function deleteFiles($filename, $dirname)
	{
		$webroot = APP."/webroot/";
		# Expects webroot prepended to filename. adds if not.
		if(!preg_match("@^$webroot@", $dirname))
		{
			$dirname = $webroot.$dirname;
		}

		if(file_exists("$dirname/$filename"))
		{
			@unlink("$dirname/$filename"); # Base file.
			# Silently ignore if can't remove file since not there, etc...
		}

		# Thumbnails. in arbitrary named dirs 200x200, 600x600, etc.
		$dir = opendir($dirname);
		while($file = readdir($dir))
		{
			if(is_dir("$dirname/$file"))
			{
				if(file_exists("$dirname/$file/$filename"))
				{
					@unlink("$dirname/$file/$filename");
				}
			}
		}
	}

	function file_required(&$model, $require = true)
	{
		$this->settings[$model->alias]['required'] = $require;
	}

	###################################
	function upload(&$model = null, $field = 'file', $prefix = null)
	# EVEN IF WE FIND SOMETHING FANCY, WE SHOULD SAVE THIS
	# $path is likely the controller/model.
	{
		# Must return FALSE if we don't want the record saved (ie no file provided)...

		$hostid = null; # If we don't implement per-site, ignore and put at top-level (ie support_answer_photos, etc)
		$modelpath = Inflector::pluralize(Inflector::tableize($model->alias));

		$this->settings[$model->alias]['basedir'] = "{$this->settings[$model->alias]['dir']}/$modelpath"; # Fallback, webroot/uploads/PagePhoto

		error_log("MODEL={$model->alias}, PATH=$modelpath");

		$hostid = null;

		error_log("DATA=".print_r($model->data,true));

		if($model->hasField("site_id")) # Required
		{
			if(isset($model->data[$model->alias]['site_id']))  # Already set
			{
				error_log("HOST_IDSET");
				$hostid = $model->data[$model->alias]['site_id'];
			} else { # Must be on www/blog/etc...
			/*
				error_log("HJOST_ID NOT SET");
				$hostid = Configure::read("site_id");
				if(!is_integer($hostid)) # Could be '0'
				{
					trigger_error('Upload Error: Unable to determine site');
				}
			*/
			}

			$this->settings[$model->alias]['basedir'] = "{$this->settings[$model->alias]['dir']}/$hostid/$modelpath";
			# XXX we need to know this,  otherwise there's a problem...
			# applies to user account?  some other way?

			# Should be able to SHARE same photo between site and forum
			# then we need to figure out what their site SHOULD be! passing site_id in upload
		}

		error_log("BASEDIR=".$this->settings[$model->alias]['basedir']);

		if(empty($field)) { $field = $this->settings[$model->alias]['field']; }

		error_log("FILES=".print_r($_FILES,true));

		$required = !empty($this->settings[$model->alias]['required']) ? $this->settings[$model->alias]['required'] : null;

		if(empty($model->data[$model->alias][$field]) && !empty($model->id)) # Already saved and just saving an individual field.
		{
			error_log("SKIPPING EXISTING FILE, NO NEED TO CHECK UPLOAD");
			return true;
		}

		$file_meta = !empty($model->data[$model->alias][$field]) ?  $model->data[$model->alias][$field] : null;
		#error_log("DATA ({$model->alias})=".print_r($model->data,true));

		if(!isset($file_meta['error'])) {
			error_log("ERROR=".print_r($required,true));
			if(!empty($required) && (!is_array($required) || in_array($field, $required)))
			{
				error_log("NO UPLOAD ($field), ALERT"); 
				$this->validationErrors[$field][] = "No file was provided";
				return false;
			} else {
				error_log("NO UPLOAD {$model->alias} ($field), SILENT"); 
				return true; 
			}
		} # No file specified/needed. just updating other stuff...

		# This here conflicts with serializeable.....
		# thus, some field names are reservered to not be serialized!
		# or disalbe serializeable from use

		#error_log("FILE_META=".print_r($file_meta,true));
		#error_log("NOF=".UPLOAD_ERR_NO_FILE);

		if(empty($file_meta['tmp_name'])) { 
			error_log("NO FILE FOUND=".print_r($file_meta,true));
			
			# No file specified. could just be editing associated text for file, etc.

			if(!empty($model->data[$model->alias][$model->primaryKey])) # Existing record.
			{
				error_log("SKIPPING, EXISTING REORD");
				# XXX TODO REPLACING EXISTING IMAGE...
				# OK to skip file.
				return true;
			} else if ($file_meta['error'] == UPLOAD_ERR_NO_FILE) { 
				error_log("NO FILE PROVIDED, RESUMING NORMAL CODE (if this is option, did you mean to set required=>false in the behavior? required=>true is default................");
				# Seems like they didnt want to upload anything....

				# OK if auxiliary record, BAD if main.
				# Whether file absense will be ignored or required
				if(!empty($this->settings[$model->alias]['required']))
				{
					$model->validationErrors[$field][] = "No file provided";
					
					return false;
				} else {
					return true; # Not given and not needed.
				}

			} else {
				error_log("REAL ERROR {$file_meta['error']} ");
				$error_str = "Could not save file. ";
				switch ($file_meta['error'])
				{
					case UPLOAD_ERR_INI_SIZE:
						$max = ini_get("upload_max_filesize");
						$error_str .= "File is too big (> $max).";
						break;
					#case UPLOAD_ERR_NO_FILE:
					#	$error_str .= "No file was specified.";
					#	break;
					case UPLOAD_ERR_CANT_WRITE:
						$error_str .= "Cannot write to disk.";
						break;
					case UPLOAD_ERR_OK:
						break; # good.
					default:
						$error_str .= "Unknown error.";
						break;
				}
				error_log("ERRSTR=$error_str");
				# Adding, not ok to skip file.
				$model->validationErrors[$field][] = $error_str;
				return false;
			}
		}

		# Check mime type if restricted.
		#error_log("MT=".print_r($this->mime_types,true));

		if(!empty($this->settings[$model->alias]['mime_types']))
		{
			if(!in_array($file_meta['type'], $this->settings[$model->alias]['mime_types']))
			{
				$model->validationErrors[$field][] = "Invalid file format, not allowed.";#(type of file is not allowed).";
				return false;
				
			}
		}

		$file_name = $file_meta['name'];
		$file_name_parts = explode(".", $file_name);
		$file_ext = $file_name_parts[count($file_name_parts)-1];

		# instead of hostname, so files dont have to move
		$content = file_get_contents($file_meta['tmp_name']);

		error_log("CONTENT_LEN=".strlen($content));

		if($model->hasField("filename")) # Save on disk.
		{
			if($outfile = $this->saveToDisk($model, $field, $content, $file_meta, $prefix))
			{
				$model->data[$model->alias]['path'] = dirname($outfile);
				$model->data[$model->alias]['filename'] = basename($outfile);
			} else {
				return false;
			}
		} else if ($model->hasField("content")) { # Save in db.
			$model->data[$model->alias]['content'] = base64_encode($content);
		}

		if($model->hasField('name'))
		{
			$model->data[$model->alias]['name'] = $file_meta['name'];
		}
		if($model->hasField('ext')) # Important when downloading. Unless we are explicit with filename
		{
			$model->data[$model->alias]['ext'] = $file_ext;
		}
		if($model->hasField('size'))
		{
			$model->data[$model->alias]['size'] = $file_meta['size'];
		}
		if($model->hasField('type'))
		{
			$model->data[$model->alias]['type'] = $file_meta['type'];
		}

		error_log("UPLOAD RETURNING TRUE, MODEL_DATA=".print_r($model->data[$model->alias],true));

		return true;
	}

	function saveToDisk($model, $field, $content, $file_meta, $prefix = null)
	{
		if(empty($prefix))
		{
			$prefix = time().rand();
		}
		$file_name_parts = explode(".", $file_meta['name']);
		$file_ext = $file_name_parts[count($file_name_parts)-1];

		# ALL FILES GO UNDER uploads/

		$outfile = "{$this->uploadFolder}/{$this->settings[$model->alias]['basedir']}/$prefix.$file_ext";

		$outdir = dirname(APP."/webroot/$outfile");
		if(!is_dir($outdir))
		{
			if(!@mkdir($outdir, 0777, true)) # Make dir if needed.
			{
				error_log("MKDIR ($outdir) FAILED");
				$model->validationErrors[$field][] = "Could not create directory $outdir";
				return false;
			}
		}


		$absoutfile = APP."/webroot/$outfile";

		error_log("MOVING FILE FROM={$file_meta['tmp_name']} TO $absoutfile");

		#if(!@rename($file_meta['tmp_name'], $absoutfile)) # Since we may use XHR uploads
		if(!@file_put_contents($absoutfile, $content)) # Since we may use XHR uploads
		{

			error_log("CANT WRITE TO $absoutfile (LEN=".strlen($content).")");
			$model->validationErrors[$field][] =
				"Unable to move file from temporary location."; # File not found (bad code) or disk permissions.
			# Need way to get over properly.
			return false;
		}
		chmod($absoutfile, 0644); # Fix permissions, since default is 0600 per uploads.

		return $outfile;
	}

	function filename($model, $id = null)
	{
		if(!empty($id)) { $model->id = $id; } # Otherwise assume in $this->id
		$file = $model->read(null, $id);
		if(empty($file)) { return false; }
		$abspath = APP."/webroot/".$file[$model->alias]['path']."/".$file[$model->alias]['filename'];
		return $abspath;
	}

	function echoFileContent($model, $id = null, $forceDownload = false, $friendlyName = null) # TODO force download?
	{
		$file = $model->read(null, $id);
		$mime = $file[$model->alias]['type'];
		if($forceDownload || empty($mime)) { $mime = 'application/octet-stream'; }
		$ext = !empty($file[$model->alias]['ext']) ? $file[$model->alias]['ext'] : null;
		if(empty($friendlyName))
		{
			$friendlyName = $file[$model->alias][$model->displayField].(!empty($ext)?".$ext":"");
		}

		$content = null;
		if($model->hasField("content")) { 
			$content = base64_decode($file[$model->alias]['content']); # Stored in db.
		} else {
			$abspath = $this->filename($model, $id);
	
			# Assure file is there....
			if(!file_exists($abspath) || !($content = file_get_contents($abspath)))
			{
				return false;
			}
		}

		header("Content-Type: $mime");
		header("Content-Disposition: inline; filename=$friendlyName");
		echo $content;
		exit(0);
	}

}
