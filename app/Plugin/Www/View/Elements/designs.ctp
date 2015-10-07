<? 
App::uses('Folder', 'Utility');
$path  = APP."/Plugin/Rescue/webroot/images/designs";
$folder = new Folder($path);
$designs = $folder->find('.*\.jpg');
usort($designs, function($a,$b) { 
	list($an,$aext) = explode(".", $a);
	list($bn,$bext) = explode(".", $b);
	if($an == $bn) { return 0; }
	return $an < $bn ? -1 : 1;
});
?>
<? foreach($designs as $file) { list($name,$ext) = explode(".", $file);
?>
	<div id='Design_<?= $name ?>' class='col-md-3 center_align design marginbottom25'>
		<?= $this->Html->link($this->Html->image("/rescue/images/designs/thumbs/$file",array('class'=>'width100p')), "/rescue/images/designs/large/$file",array('class'=>'lightbox','title'=>"Template $name"));  ?>
		<br/>
		<?= $this->Html->link("Template $name", "/rescue/images/designs/large/$file",array('class'=>''));  ?>
		<? if(!empty($selectable)) { ?>
		<br/>
		<?= $this->Html->link("Select", "javascript:void(0)", array('class'=>'btn btn-success')); ?>
		<? } ?>
	</div>
<? } ?>
