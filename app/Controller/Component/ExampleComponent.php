<?
class ExampleComponent extends Component
{
	var $public_methods = array('admin_autofill', 'admin_random');
	# Available to any controller

	var $hostname = null;

	var $uses = array(
		'Site',
		'User',
		'SiteDesign',
		'Homepage',
		'AboutPage',
		'ContactPage',
		'Contact',
		'Page',
		'NewsPost',
		'Event',
		'EventContact',
		'EventLocation',
		'PhotoAlbum',
		'Photo',
		'PagePhoto',
		'Link',
		'LinkCategory',
		'Download',
		'SitePageView',
		'SiteVisit'
	);

	function initialize(Controller $controller)
	{
		$this->controller = $controller;
		foreach($this->uses as $model)
		{
			list($plugin,$modelClass) = pluginSplit($model);
			$this->controller->loadModel($model);
			#echo "LOADING $modelClass\n";
			$this->{$modelClass} = $this->controller->{$modelClass};
		}
	}

	function admin_autofill($modelClass = null, $refid = null)
	{ # Whole form.
		return $this->read($modelClass, $refid);
	}

	function admin_random($fieldName) # title or Page.title
	{
		 list($modelClass, $field) = pluginSplit($fieldName);
		 if(empty($modelClass)) { $modelClass = $this->controller->modelClass; }
		 $global_func = "random_$field";
		 $model_func = "random_".Inflector::underscore($modelClass)."_$field";
		 # random_title or random_page_title

		 if(method_exists($this, $global_func))
		 {
		 	return $this->{$global_func}();
		 }
		 else if(method_exists($this, $model_func))
		 {
		 	return $this->{$model_func}();
		 } else { # Try lorem
		 	return $this->admin_lorem($fieldName);
		 }
	}

	# TODO autofill a single field...
	# Probably only for text field and areas.... 
	# dates, numbers, etc can be faked/randomized by hand.
	function admin_lorem($field) # Really only for big stuff
	{
		 list($modelClass, $fieldName) = pluginSplit($field);
		 if(empty($modelClass)) { $modelClass = $this->controller->modelClass; }
		 # Determine by field type and length
		 # ie text blob, text field, etc...

		 $fieldType = $this->{$modelClass}->type($field);

		 $length = ($fieldType == 'text') ? 100 : 1000; 
		 # Whole page content vs blob.

		 return $this->lorem($length);
	}

	function randomize($modelClass = null, $refid = null)
	{
		if(empty($this->hostname) && !empty($this->Session))
		{
			$this->hostname = $this->Session->read("CurrentSite.Site.hostname");
		}

		if(empty($modelClass))
		{
			$modelClass = $this->controller->modelClass; 
			# Default to self.
		}
		$model = Inflector::underscore($modelClass);
		if(method_exists($this, "randomize_$model"))
		{
			return $this->{"randomize_$model"}($refid);
		}
	}

	function randomize_user()
	{
		$first = $this->random_first();
		$last = $this->random_last();
		$password = $this->password(strtolower($first)."1");

		$email = strtolower($first)."@".$this->domain();

		$user = array(
			'first_name'=>$first,
			'last_name'=>$last,
			#'title'=>$this->random_person_title(),
			'admin'=>rand(0,1),
			'email'=>$email,
			'password'=>$password,
			'last_login'=>(rand(0,1) ? $this->random_date() : null),
			'login_count'=>(rand(0,1) ? rand(1,20) : 0)
		);

		return $user;
	}

	function randomize_homepage($title = 'Welcome')
	{
		$photo_id = $this->random_page_photo();

		$homepage = array(
			'title'=>$title,
			'page_photo_id'=>$photo_id,
			'introduction'=>$this->lorem(rand(200,400))
		);

		return $homepage;
	}

	function randomize_about_page()
	{
		$photo_id = $this->random_page_photo();
		$year = $this->randyear();
		$about = array(
			'page_photo_id'=>$photo_id,
			'overview'=>"Our mission is to ".$this->lorem(rand(100,200),false),
			'history'=>"Since $year, ".$this->lorem(rand(150,250),false),
			#'content'=>$this->lorem(rand(200,400)),
		);
		return $about;
	}

	function randomize_contact_page()
	{
		$hostname = $this->hostname;
		$year = $this->randyear();

		list($address, $address2, $city, $state, $zip) = $this->random_fulladdress();

		$contact = array(
			'show_map'=>rand(0,1),
			'address'=>$address,
			#'address2'=>$address2,
			'city'=>$city,
			'state'=>$state,
			'zip_code'=>$zip,
			'show_map'=>1,
			'phone'=>$this->random_phone(),
			'fax'=>$this->random_phone(),
			'email'=>"admin@$hostname.com",
		);

		return $contact;
	}

	function randomize_contact()
	{
		# Get first contact page id.
		$contact_page_id = $this->ContactPage->find('first_id');

		$groupName = $this->random_group_name();
		$group = $this->random_line("users/title_group.txt");
		$group_host = preg_replace("/ /", "-", strtolower($group));

		$hostname = $this->hostname;

		$item = array(
			#'contact_page_id'=>$contact_page_id,
			'name'=>$this->random_fullname(),
			'title'=>$this->random_person_title(),
			'phone'=>$this->random_phone(),
			'email'=>$this->random_email($hostname),
			#'url'=>rand(0,1) ? "http://$group_host.$hostname.com/" : null,
			'comments'=>rand(0, 1) ? $this->lorem(30) : null,
		);

		return $item;
	}

	function randomize_topic()
	{
		$photo_id = $this->random_page_photo();
		$topic = array(
			'title'=>$this->random_topic_title(),
			'page_photo_id'=>$photo_id,
			#'summary'=>$this->lorem(200),
			'content'=>$this->lorem(1000),
			#'status'=>'published',
			'parent_id'=>null,

		);
		return $topic;
	}

	function randomize_subpage($pid)
	{
			$photo_id = rand(0,1) ? $this->random_page_photo() : null;
			
			$subpage = array(
				'title'=>$this->random_page_title(),
				'page_photo_id'=>$photo_id,
				#'status'=>'published',
				'parent_id'=>$pid,
				#'summary'=>$this->lorem(100),
				'content'=>$this->lorem(1000)
			);

		return $subpage;
	}

	function randomize_project()
	{
			$photo_id = $this->random_page_photo();
			$project = array(
				'title'=>$this->random_project_title(),
				'page_photo_id'=>$photo_id,
				#'summary'=>$this->lorem(200),
				'content'=>$this->lorem(1000),
				#'status'=>'published',
				#'parent_id'=>null,
			);

			return $project;
	}

	function randomize_member_page()
	{
			$photo_id = $this->random_page_photo();
			$project = array(
				'title'=>'Members Only',
				'page_photo_id'=>$photo_id,
				'description'=>$this->lorem(1000),
				'enabled'=>1
			);

			return $project;
	}

	function randomize_page()#$type = 'Page')
	# THIS IS WHERE WE SAY page vs project vs topic....
	{
		/*
		$type = strtolower(Inflector::underscore($type));
		if($type == 'project') { return $this->randomize_project(); }
		if($type == 'topic') { return $this->randomize_topic(); }
		*/

			$photo_id = $this->random_page_photo();
			$page = array(
				#'type'=>'page',
				'title'=>$this->random_page_title(),
				'page_photo_id'=>$photo_id,
				#'summary'=>$this->lorem(200),
				'content'=>$this->lorem(1000),
				#'status'=>'published',
				'parent_id'=>0,
				#'published'=>$this->random_date(),

			);

			return $page;
	}

	function randomize_news_post()
	{
			$photo_id = rand(0,1) ? $this->random_page_photo() : null;
			$newsPost = array(
				'title'=>"News Post ".rand(100,5000),
				'page_photo_id'=>$photo_id,
				#'summary'=>$this->lorem(200),
				'content'=>$this->lorem(1000),
				#'status'=>'published',
				#'published'=>$this->random_/ate(),
			);

			return $newsPost;
	}

	function randomize_event()
	{
		# Get all location ids
		$locationIDs = $this->EventLocation->fields("id");

		# Get all contact ids
		$contactIDs = $this->EventContact->fields("id");

			# Get random
			$photo_id = rand(0,1) ? $this->random_page_photo() : null;
			$contact_id = $contactIDs[rand(0,count($contactIDs)-1)];
			$location_id = $locationIDs[rand(0,count($locationIDs)-1)];
			$start_date = $this->random_date('+1 day', '+6 months');
			$start_time = $this->random_time();
			$randdays = rand(0,1) ? 0 : rand(0,14);
			$randhours = rand(0,1) ? 0 : rand(0,6);
			$end_date = date("Y-m-d H:i:s", strtotime("$start_date +$randdays days"));
			$end_time = date("H:i:s", strtotime("$start_time +$randhours hours"));

			$event = array(
				'title'=>"Event ".rand(100,5000),
				'page_photo_id'=>$photo_id,
				'summary'=>$this->lorem(200),
				'description'=>$this->lorem(1000),
				#'status'=>'published',
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'start_time'=>$start_time,
				'end_time'=>$end_time,
				'event_location_id'=>$location_id,
				'event_contact_id'=>$contact_id
			);
			
			return $event;
	}

	function randomize_event_location()
	{
			$location = array(
				'name'=>$this->random_location(),
				'address'=>$this->randaddress(),
				'city'=>$this->randcity(),
				'state'=>$this->randstate(),
				'zip_code'=>$this->randzip(),
				'phone'=>$this->random_phone(),
			);

			return $location;
	}

	function randomize_event_contact()
	{
		$contact = array(
			'name'=>rand(0,1) ? $this->random_person_title() : $this->random_fullname(),
			'phone'=>$this->random_phone(),
			'email'=>$this->random_email($hostname),
		);

		return $contact;
	}

	function randomize_photos($album_id) # Filling in a photo album
	{
		# Now add random photos to albums.
		$photo_total = rand(6, 30);
		for($j = 0; $j < $photo_total; $j++)
		{
			$this->random_photo($album_id);
		}

		# Now the page just needs to be refreshed, 
		# since album_id is in photo record.
	}

	function randomize_links()
	{
		#$linkPage = $this->LinkPage->find('first_default'); # auto-create
		#$page_id = $linkPage['LinkPage']['id'];

		# Create categories.
		$maxcats = 9;

		$existingCats = $this->LinkCategory->find('count');

		$totalcats = rand(3, $maxcats);

		for($i = 0; $i < $totalcats - $existingCats; $i++)
		{

			$cat = $this->randomize_link_category();

			$this->save("LinkCategory", $cat);
		}

		$catIDs = $this->LinkCategory->fields('id');


		# Create links, random category, or none.

		$total = !empty($total) ? $total : rand(4, 25);

		for($i = 0; $i < $total; $i++)
		{
			$catid = rand(0,1) ? $catIDs[rand(0,count($catIDs)-1)] : null;

			$link = $this->randomize_link($catid);
			$this->save("Link", $link);
		}
	}

	function randomize_link($catid = null) # Can be used to fill in form.
	{
		#$linkPage = $this->LinkPage->find('first_default'); # auto-create
		#$page_id = $linkPage['LinkPage']['id'];

		$title = "Example Link " . rand(10,600);#ucwords(preg_replace("/\n/", " ", $this->lorem(rand(15,35), false)));
			$link = array(
				#'link_page_id'=>$page_id,
				'title'=>$title,
				'description'=>$this->lorem(rand(50,100)),
				'link_category_id'=>$catid,
				'url'=>$this->random_line("urls.txt")
			);

		return $link;
	}

	function randomize_link_category() # Random link category generator...
	{
		#$page_id = $this->LinkPage->find('first_id'); 
			$cat = array(
				'title'=>'Category '.rand(1,25),
				#'link_page_id'=>$page_id,
			);
			return $cat;
	}

	# File uploads auto fill from content anyway, so no need to fake.
	# As long as I have the sample file folder handy...
	#
	function randomize_download_category()
	{
		#$linkPage = $this->FilePage->find('first_id'); 
		$cat = array(
			'title'=>'Category '.rand(1,25),
			#'file_page_id'=>$page_id,
		);

		return $cat;
	}

	function randomize_video()
	{
		$video_url = $this->random_line("videos.txt");
		return array('video_url'=>$video_url);
	}

	# Audio can be filled in manually, assuming I have folder handy.



	##################################################

	function save($modelClass, $data)
	{
		$this->{$modelClass}->create();
		# Place in random created/modified date....
		if($this->{$modelClass}->hasField("created") && !isset($data['created']))
		{
			$data['created'] = $this->random_date('3 weeks ago');
		}
		if($this->{$modelClass}->hasField("modified") && !isset($data['modified']))
		{
			$data['modified'] = $this->random_date($data['created']);
		}
		$this->{$modelClass}->save(array($modelClass=>$data));
		return $this->{$modelClass}->id;
	}

	###########################################
	# Generation of data pieces/fields

	function random_location()
	{
		$type = $this->random_line("location_type.txt");
		$name = $this->random_line("users/title_group.txt");
		return "$name $type";
	}

	function random_page_photo()
	{ # Might be specific to about_pages, site_templates/logos, etc.
		$site_id = $this->get_site_id();

		$path = 'page_photos'; 

		$upload = $this->random_upload($path);
		$upload['caption'] = $this->lorem(50);
		$upload['title'] = ucwords($this->lorem(25));

		$this->save("PagePhoto", $upload);
		return $this->PagePhoto->id;
	}

	function random_photo($album_id = null) # for album
	{ # Might be specific to about_pages, site_templates/logos, etc.
		$site_id = $this->get_site_id();

		$path = 'photos'; 

		$upload = $this->random_upload($path);
		$upload['photo_album_id'] = $album_id;
		$upload['caption'] = $this->lorem(50);
		$upload['title'] = ucwords($this->lorem(25));

		$this->save("Photo", $upload);
		return $this->Photo->id;
	}

	function random_upload($path = 'photos')
	{
		$randfile = $this->random_filename($path);
		list($prefix, $ext) = preg_split("/[.]/", $randfile);
		$type = $this->mime_type($this->abspath($randfile));
		$size = filesize($this->abspath($randfile));

		$path = $this->upload_path($path);
		$randname = time().rand(0,100000);
		$filename = "$randname.$ext";
		$absdestfile = APP."/webroot/$path/$filename";

		$absdir = dirname($absdestfile);
		$this->copy($this->abspath($randfile), $absdestfile);

		list($name, $ext) = preg_split("/[.]/", basename($randfile));

		$title = Inflector::humanize(Inflector::underscore($name)) . " " . 
			#strtoupper($ext) . " " . 
			rand(100,5000);

		$upload = array(
			'name'=>basename($randfile),
			'path'=>$path,
			'filename'=>$filename,
			'ext'=>$ext,
			'type'=>$type,
			'size'=>$size,
			'title'=>$title,
		);
		return $upload;
	}

	function random_line($file)
	{
		$example_file = $this->abspath($file);
		$content = file_get_contents($example_file);
		$lines = split("\n", $content);
		# Remove entries that are empty.
		$lines = array_filter($lines);
		$random = rand(0, count($lines)-1);
		#echo "L=".count($lines);
		return !empty($lines[$random]) ? trim($lines[$random]) : "";
	}

	function dirfiles($path)
	{
		$files = array();
		$abspath = $this->abspath($path);
		$this->out("LOADING DIR $abspath...");
		$dir = opendir($abspath);
		while($file = readdir($dir))
		{
			if(is_file("$abspath/$file")) # Skips . .. and subdirs
			{
				$files[] = $file;
			}
		}
		$this->out(count($files) . " FILES FOUND");
		return $files;
	}

	function random_filename($path)
	{
		$files = $this->dirfiles($path);
		$randfile = $files[rand(0,count($files)-1)];
		error_log("RANDOM FILE $path = $randfile");
		return "$path/$randfile";
	}

	function random_file_content($path) # ie page lorem ipsum, etc.
	{
		$file = $this->random_filename($path);
		return file_get_contents($path);
	}

	function printperms($path)
	{
		$perms = substr(sprintf('%o', fileperms($path)), -4);
		error_log("..... PERMS $path = $perms");
	}

	# ENSURE files/dirs are modifiable by www-data group...
	function mkdir($path) # Perms MUST be prefixed by 0, even if sticky bits set! 02775 instead of 2775 !
	{
		if(!file_exists($path)) { 
			error_log("MKDIR $path 2775");
			mkdir($path, 02775, true); # 2 = setgid, files saved as group, even if secondary.
			chmod($path, 02775); # Just in case. MKDIR doesnt seem to play nicely enough.
		}

		// calling shell script should be chgrp www-data and chmod g+s
		// so mkdir and file saves go under www-data
		// so server can work with fake site upload folders

		# Ensure writeable by www-data
		$gid = filegroup($path);
		$group = posix_getgrgid($gid);
		if(empty($group['name']) || $group['name'] != 'www-data')
		{
			error_log("CHGRP $path to www-data, WRONG PERMS");
			if(!chgrp($path, "www-data"))
			{
				error_log("*************** COULDN'T SET www-data GROUP PERMS FOR $path ****************");
			}
		}
	}

	function copy($src, $dest)
	{
		$destdir = dirname($dest);
		$this->mkdir($destdir);
		error_log("COPYING $src to $dest w/CHMOD");
		copy($src, $dest);
		chmod($dest, 0664);
	}

	function upload_path($dir)
	{
		$site_id = $this->get_site_id();
		if(empty($site_id))
		{
			error_log("Cannot determine site for upload file path...");
			exit(1);
		}
		$uploadRoot = "uploads/$site_id";

		# Make sure dir is writable by www-data
		$this->mkdir(APP."/webroot/$uploadRoot");

		return "$uploadRoot/$dir";
	}

	function random_title()
	{
		return $this->lorem(50);
	}
	function random_summary()
	{
		return $this->lorem(200);
	}
	function random_description() # Probably a list item.
	{
		return $this->lorem(200);
	}
	function random_content()
	{
		return $this->lorem(2000);
	}

	function lorem($length = 500, $para = true)
	{
		$lorem = file_get_contents($this->abspath("lorem.txt"));
		$start = rand(0, strlen($lorem)-$length); # random Slice 
		$string = trim(substr($lorem, $start, $length));
		if(!empty($para)) # Like a sentence.
		{
			return ucfirst("$string.");
		} else {
			return $string;
		}
	}

	function domain($hostname = null)
	{
		if(empty($hostname)) { $hostname = $this->hostname; }

		return "$hostname.com";
	}

	function randaddress()
	{
		$street = $this->random_line("streets.txt"); # Ought to be common for map to find.
		$types = array(
			'Avenue',
			'Road',
			'Blvd',
			'St',
			'Lane',
			'Court',
			'Circle',
		);

		$type = $types[rand(0,count($types)-1)];

		$number = rand(25, 2000);

		return "$number $street $type";
	}

	function randcity()
	{
		return $this->random_line("cities.txt");
	}
	function randstate()
	{
		$state = $this->random_line("states.txt");
		return $state;
	}
	function randcountry()
	{
		$country = $this->random_line("countries.txt");
		return $country;
	}


	function randzip()
	{
		return sprintf("%05u", rand(01001, 99998));
	}

	function random_fulladdress()
	{
		return preg_split("/,/", $this->random_line("addresses.txt"));
	}

	function random_phone()
	{
		return sprintf("(%03u) %03u-%04u", rand(200,699), rand(100,998), rand(100, 9999));
	}

	function password($password) # Encrypts password
	{
		return $pwenc = Security::hash($password, null, true);
	}
	function randyear($after = 1970)
	{
		return rand($after, date("Y")-1);
	}

	function random_date($from = '1 month ago', $to = 'now')
	{ # Dates MUST be numeric, ie '1 month ago' NOT 'one month ago'
		# Allow for custom back dates.
		if(!empty($this->params['from']))
		{
			$from = $this->params['from'];
		}
		if(!empty($this->params['to']))
		{
			$to = $this->params['to'];
		}
		#error_log("FROM=$from, TO=$to");
		$start = strtotime($from);
		$finish = strtotime($to);
		$date = date("Y-m-d H:i:s", rand($start, $finish));
		error_log("DATE BETWEEN $from ($start) - $to ($finish) = $date");
		return $date;
	}

	function random_time()
	{
		$start = strtotime("6am");
		$finish = strtotime("8pm");
		return date("H:i:s", rand($start, $finish));
	}
	function random_first()
	{
		return $this->random_line("users/first_names.txt");
	}
	function random_last()
	{
		return $this->random_line("users/last_names.txt");
	}
	function random_fullname()
	{
		return $this->random_first() ." " . $this->random_last();
	}

	function random_email($hostname = 'example')
	{
		$randname = strtolower($this->random_first());
		return "$randname@$hostname.com";
	}

	function random_person_title()
	{
		$rank = $this->random_line("users/title_rank.txt");
		$group = $this->random_line("users/title_group.txt");
		$role = $this->random_line("users/title_role.txt");

		return "$rank $group $role";
	}

	function random_group_name()
	{
		$group = $this->random_line("users/title_group.txt");
		$role = Inflector::pluralize($this->random_line("users/title_role.txt"));
		return "$group $role";
	}

	function random_page_title()
	{
		return "Example page ".rand(1,24);

		#$title = $this->random_line("page_titles.txt");
		#return $title;
	}

	function random_topic_title()
	{
		$title = $this->random_line("topic_titles.txt");
		return $title;
	}

	function random_project_title()
	{
		$title = $this->random_line("project_titles.txt");
		return $title;
	}

	function random_ip()
	{
		return sprintf("%u.%u.%u.%u", rand(1,249), rand(1,249), rand(1,249), rand(1,249));
	}

	function random_internal_url()
	{
		$models = array('NewsPost','Event','Page','PhotoAlbum','AboutPage','ContactPage','Homepage');

		$modelClass = $models[rand(0,count($models)-1)];
		$controller = Inflector::pluralize(Inflector::underscore($modelClass));

		# Get something actual in system...
		# But try to get some common ones often.
		# but somehow favor some over others
		# (half the time, get one of the first 5 in the list...)
		
		$id = $this->{$modelClass}->find('first_id', array(
			'recursive'=>-1, # Don't slow down...
			'offset'=>rand(0,5), # One of first 5 in list.
			'order'=>rand(0,1) ? "$modelClass.id" : 'RAND()' # Half random, half same 5 to choose from.
		));

		$url = array('controller'=>$controller,'action'=>'view', $id);
		error_log("ID=$id, URL=".print_r($url,true));
		return $url;
	}

	function random_ref_keywords()
	{
		return $this->random_line("keywords.txt");
	}

	function random_ref_path()
	{
		return $this->random_line("refpath.txt"); # hostname without http://
	}


	function random_ref_domain()
	{
		return $this->random_line("referer.txt"); # hostname without http://
	}

	function random_session_id()
	{
		return time().rand(1000,999999);
	}

	function random_browser()
	{
		return $this->random_line("browser.txt");
	}

	function abspath($file)
	{
		return dirname(__FILE__)."/example/$file";
	}

	function mime_type($file)
	{
		$fi = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($fi, $file);
		error_log("MIME CHECK $file = $mime_type");
		return $mime_type;
	}

	function get_site_id()
	{
		return Configure::read("site_id");
	}

	function set_hostname($hostname)
	{ # We just need to get site_id for db saves, etc.
		$this->hostname = $hostname; # So IT knows.

		if(empty($this->site_id))
		{
			$this->Site->id = null;
			$this->out("SEARCHING FOR $hostname...");
			$this->site_id = $this->Site->field("id", array('hostname'=>$hostname));

			$this->out("FOUND SITE_ID: {$this->site_id}");
			Configure::write("site_id", $this->site_id); # For models.
		}
		error_log("SETTIN HOST=$hostname, ID={$this->site_id}");
		return $this->site_id;
	}

	function out($msg)
	{
		error_log($msg);
	}



}
