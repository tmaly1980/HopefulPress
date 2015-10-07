<?
class FaviconController extends Controller
{
	function icon($plugin = null)
	{
		$path = $plugin ? APP."/Plugin/$plugin/webroot/favicon.ico" : APP."/webroot/favicon.ico";
		$icon = file_get_contents($path);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $path);
		header("Content-Type: $mime");
		echo $icon;
		exit(0);
	}
}
