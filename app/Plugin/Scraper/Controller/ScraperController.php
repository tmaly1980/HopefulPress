<?
App::uses("HttpSocket", "Network/Http");

class ScraperController extends AppController
{
	var $components = array('Core.Json');
	var $uses = array();

	function index() # A url cant really be passed as url w/o screwing up orig url
	{
		$url = !empty($_REQUEST['link_url']) ? $_REQUEST['link_url'] : null;

		if(!empty($url))
		{
			# Get title...
			$absurl = $url;
			if(preg_match("@^/@", $url)) { # Relative to site.
				$absurl = "http://".$_SERVER['HTTP_HOST']."$url";
			} else if (preg_match("@^\w+://@", $url) && !preg_match("@^http(s)?://@", $url)) { 
				return; # Something else, not supported.
			} else if (!preg_match("@^\w+://@", $url)) { # Forgot http://{
				$absurl = "http://$url";
			}
			# Now append a slash if needed. (not a single one outside of prefix in url)
			if(!preg_match("@\w/@", $absurl))
			{
				$absurl = "$absurl/";
			}

			#error_log("USING URL=$absurl");


			list($page,$status) = $this->get_webpage_content($absurl,true);
			if(!$page) { error_log("COULDNT GET $absurl"); return; }

			if($status < 299)
			# Ignore 'moved' 302, 404, etc...
			{
				$tidy = new tidy();
				$tidy->parseString($page, array('output-xhtml'=>true,'indent'=>true), 'utf8');
				# Needs to be xhtml for xpath to work.... some pages are awful and old/hand html.
				$tidy->cleanRepair();

				$html = (string) $tidy;

				$doc = new DOMDocument();
				libxml_use_internal_errors(true); // avoid duplicate id errors...

				$rc = $doc->loadHTML($html);

				$page = new DOMXPath($doc);
				$title_nodes = $page->query("//head/title");
				if($title_nodes && $title_nodes->length)
				{
					$title = trim($title_nodes->item(0)->textContent);
					$this->Json->set("title", $title);
				}

				# Try to get description.
				$desc = "";
				$desc_nodes = $page->query("//head//meta[@name='description']");
				#print_r($desc_nodes->length);
				if($desc_nodes && $desc_nodes->length)
				{
					$desc = $desc_nodes->item(0)->getAttribute("content");
				}
				if(empty($desc)) # Try first paragraph found.
				{
					$desc_nodes = $page->query("//p | //b | //i | //span | //h2 | //h3 | //h4");
					if($desc_nodes && $desc_nodes->length)
					{
						foreach($desc_nodes as $desc_node)
						{
							$content = preg_replace('/[\x0A]/', '', trim($desc_node->textContent));

        						$content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);
        						$content = preg_replace('/\t+/', ' ', $content);
        						$content = preg_replace('/\s+/', ' ', $content);
							$content = trim($content);

							if($content) { 
								$desc = $content;
								break;
							}
						}
					}
				}
				$this->Json->set("description", $desc);
			}
		}

		return $this->Json->render();
	}
}
