<?php
App::uses('HttpSocket', 'Network/Http');

class Video extends AppModel {
	var $displayField = 'title';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	#var $virtualFields = array('date'=>'Video.created');
	var $order = 'Video.id DESC';

	/*var $actsAs = array(
		'Updatable',
		'Projectable'
	);*/

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		#'MemberPages.Member',
		/*
		'VideoCategory' => array(
			'className' => 'VideoCategory',
			'foreignKey' => 'video_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		*/
	);

	function beforeSave($options = array()) # We can handle other url's but need to know what they are to parse so we can embed.
	{
		if(!empty($this->data[$this->alias]['video_url']))
		{
			$video_location = $this->get_video_location($this->data[$this->alias]['video_url']);
			if(!empty($video_location))
			{
				$this->data[$this->alias]['video_site'] = $video_location[0];
				$this->data[$this->alias]['video_id'] = $video_location[1];
			}
		}

		return parent::beforeSave(); # FUCKER
	}

	function get_video_location($url)
	{
		# Parse video url to extract ID for embeddable location

		# http://www.youtube.com/watch?v=phjIBD2yR-w
		if(preg_match("/youtube.com.*v[=\/]([a-zA-Z0-9_-]+)/", $url, $urlmatch))
		{
			return array('youtube.com', $urlmatch[1]);
		}
		return array();
	}

	function beforeFind($query)
	{
		# Filter out invalid/incomplete videos...
		# If not admin, or getting widget (all/index ok)
		if(!Configure::read("in_admin") || in_array($this->findQueryType, array('latest','count')))
		{
			$query['conditions'][] = "{$this->alias}.video_id IS NOT NULL";
			$query['conditions'][] = "{$this->alias}.video_site IS NOT NULL";
		}
		return parent::beforeFind($query);
	}

	function details($data, $id = null) # Requires Video.video_url (or url as string)
	{ # Preliminary default information found from server. Can be overwritten after.

		if(!empty($data) && !is_array($data) && is_string($data))
		{
			$video_url = $data;
			$data = array($this->alias=>array(
				'video_url' => $video_url
			));
		}

		if(empty($data[$this->alias]['video_url']))
		{
			return null; 
		}
		#XXX TODO when do we save to db? url will always be there! so need other determinant....
		$video_url = $data[$this->alias]['video_url'];

		# SAMPLES:
		#
		# * google no longer uploads to video.google.com (just youtube.com) 
		##### http://animalvideos.yahoo.com/video-detail?vid=27031140&cid=24721185
		# YAHOO VIDEOS ARE A PAIN IN THE BUTT....STUPID WEIRD API
		#
		# http://youtube.com/v/dMH0bHeiRNg
		# http://www.youtube.com/user/guitarlessons#p/u/34/DGktYN7IrVI
		# http://youtube.com/?v=cQ25-glGRzI
		# http://vimeo.com/21374067 
		# http://vimeo.com/31066005

		$regex_urls = array(
			"youtube.com"=>"youtube.com.*(v[=\/]|u\/\d+\/)([a-zA-Z0-9_-]+)",
			"vimeo.com"=>"vimeo.com\/(\d+)", 
			###"yahoo.com"=>"yahoo.com/video-detail.*vid=(\d+)", 
		);

		# XXX other way to get comments?

		$video_site = $video_id = null;

		foreach($regex_urls as $site=>$regex)
		{
			if(preg_match("/$regex/", $video_url, $matches))
			{
				$video_site = $site;
				$video_id = $matches[count($matches)-1]; # The last one (may be more than one part, for variants) is the video_id
				break;
			}
		}

		if(empty($video_id)) { 
			return null;
		}

		#$video_location = $this->Video->get_video_location($video_url);
		#list($video_site, $video_id) = $video_location;

		$api_urls = array(
			"youtube.com"=>"https://gdata.youtube.com/feeds/api/videos/%s?v=2&prettyprint=true",
			"vimeo.com"=>"http://vimeo.com/api/v2/video/%s.xml",
			# MORE? TODO
		);
		# XXX do we get a full url for watching from this dataset?
		# XXX how do we send back an embeddable url? 

		$api_data = array(
			'youtube.com'=>array(
				'title'=>'//media:group//media:title', # XPATH
				'description'=>'//media:group//media:description', # XPATH
				'preview_url'=>"//media:group//media:thumbnail[@yt:name='hqdefault']/@url", // 480x360
				'thumbnail_url'=>"//media:group//media:thumbnail[@yt:name='default']/@url",
				# XXX need to extract attribute, not innerHTML
				#'url'=>'//content//@src',
			),
			'vimeo.com'=>array(
				'title'=>'//video//title', # XPATH
				'description'=>'//video//description', # XPATH
				# XXX TODO preview_url
				'thumbnail_url'=>'//video//thumbnail_small', # 100px width
				'preview_url'=>'//video//thumbnail_large', 
				#'url'=>'//video//url',
			),
		);

		$id_xpaths = array( # cHECK FOR VALIDITY
			'youtube.com'=>"//x:title", # Since they use a default namespace....
			'vimeo.com'=>"//video//id",

		);

		# XXX TODO watchable URL (just enough for embeddable player, etc)

		# XXX TODO GET DESCRIPTION....

		$video = array();
		if(!empty($id))
		{
			$videoItem = $this->read(null,$id);
			$video = $videoItem[$this->alias];
		}


		if(!empty($api_urls[$video_site]))
		{
			$api_url = preg_replace("/%s/", $video_id, $api_urls[$video_site]);

			$socket = new HttpSocket(array(
				'ssl_verify_host'=>false, # stupid google certs.
				#'ssl_cafile'=>APP."/Vendors/Google Internet Authority.crt"
			));
			$content = $socket->get($api_url);

			$this->log("RESULT=".$content);

			#$comments_url = "https://gdata.youtube.com/feeds/api/videos/$video_id/comments";
			#$comments = $socket->get($comments_url);

			$xmlobj = simplexml_load_string($content);
			error_log("VIDEO=".print_r($xmlobj,true));

			$this->log(print_r($xmlobj,true));

			$this->log("----------------------------");
			$namespaces = $xmlobj->getNamespaces(true);
			foreach($namespaces as $nspref => $nsurl)
			{
				if(empty($nspref)) { $nspref = 'x'; }
				$xmlobj->registerXPathNamespace($nspref, $nsurl);
				$this->log("NSREG=$nspref => $nsurl");
			}

			$id_xpath = !empty($id_xpaths[$video_site]) ? 
				$id_xpaths[$video_site] : null;
			$id = null; 

			if(!$id_xpath || !($id = $xmlobj->xpath($id_xpath)) || empty($id))
			{
				# Invalid video.
				$this->log("INVALID VIDEO ($id_xpath),$id, ".print_r($id,true));
				return null;
			}

			#$this->log("VIDID=".print_r($id,true));
			#$this->log(empty($id));

			$this->log(print_r($xmlobj,true));

			# STORE title, description automatically.
			foreach($api_data[$video_site] as $key=>$xpath)
			{
				if(empty($xpath)) { continue; }
				$this->log("GETPATH=$xpath");
				$values = (array) $xmlobj->xpath($xpath);
				$this->log("VALUES=".print_r($values,true));
				if(!empty($values[0]))
				{
					$this->log(print_r($values,true));
					$value = (string) $values[0]; 
					# get rid of @attributes
					$video[$key] = ($key == 'description') ? strip_tags($value) : $value;
					$this->log("GETTING1 $xpath = $value");
				} else if (!empty($values['@attributes'])) { #Wanting just a single attribute.m as per /@attr in xpath
					$value = (string) array_shift($values['@attributes']); 
					$this->log("GETTING2 $xpath = $value");
				}
			}

			$video['video_url'] = $video_url;
			$video['video_site'] = $video_site;
			$video['video_id'] = $video_id;
			$video['video_page_id'] = !empty($this->data['Video']['video_page_id']) ? $this->data['Video']['video_page_id'] : null; 

			$this->data = array($this->alias=>$video);
		} else {
			return null;
		}
		return $this->data;
	}

}
?>
