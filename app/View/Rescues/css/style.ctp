<?
App::import('Vendor', 'CakeLess.Lessc',
  array(
    'file' => 'oyejorge' . DS . 'less.php' . DS . 'lessc.inc.php'
  )
);

if(empty($theme)) { $theme = 'default'; } 

$less = $this->element("../../webroot/less/themes/$theme"); # .ctp file 
if(!empty($this->request->query['less'])) { header("Content-Type: text/plain"); echo $less; exit(0); }
$parser = new Less_Parser();
# Set file search path to start with /webroot/less, ie for import
$parser->parse($less);
$css = $parser->getCss();
echo $css;
?>
