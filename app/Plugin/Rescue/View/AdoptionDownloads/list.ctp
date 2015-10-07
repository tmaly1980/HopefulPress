<?  if(empty($downloads)) { ?>
	<div class='nodata'>
		There are no downloads yet.
	</div>
<? } else { 
$lastdate = null;
foreach($downloads as $download) { 
	if(!empty($download["AdoptionDownload"])) { $download = $download['AdoptionDownload']; } 
	$mime = $download['type'];
	list($mimeGroup, $mimeDetails) = explode("/", $mime);
	$mimeClass = preg_replace("/\W/", "_", $mime);
	$mimeGroup = preg_replace("/\W/", "_", $mimeGroup);

	$thisdate = $this->Time->monthdy($download['modified']);
	?>
	<? /* if($thisdate != $lastdate) { ?>
	<h3><?= $thisdate ?></h3>
	<? 
		$lastdate = $thisdate; # Only show one date for the day, several may be added.
	} */ ?>
	<div id="Download_<?= $download['id'] ?>" class="Download item mime <?= $mimeClass ?> <?= $mimeGroup ?>">
		<div>
			<?= $this->Html->link($download['title'], "/rescue/adoption_downloads/download/{$download['id']}/{$download['name']}", array('class'=>'')); ?>
			<? if($this->Html->can_edit($download)) { #!empty($in_admin) && ($this->Admin->access($download))) { ?>
			<span class="marginleft25 controls">
				<?= $this->Html->edit('', array('user'=>1,'controller'=>'adoption_downloads','action'=>'edit',$download['id']), array('class'=>'btn-primary btn-xs')); ?>
				&nbsp;
				<?= $this->Html->delete('', array('user'=>1,'controller'=>'adoption_downloads','action'=>'delete',$download['id']), array('class'=>'btn-danger btn-xs','confirm'=>'Are you sure you want to remove this download?')); ?>
			</span>
			<? } ?>
			<div class='clear'></div>

			<?
			$filesize = $download['size'];
			$fileunit = 'B';
			if($filesize > 1024)
			{
				$filesize /= 1024;
				$fileunit = 'KB';
			}
			if($filesize > 1024)
			{
				$filesize /= 1024;
				$fileunit = 'MB';
			}
			?>

			<div class="bold">
				(<?= !empty($download['ext']) ? strtoupper($download['ext']).", ":"" ?> <i><?= $download['name'] ?></i>, <?= sprintf("%.01u %s", $filesize, $fileunit); ?>)
			</div>
			<p class="indent">
				<?= !empty($download['description']) ? $this->Text->autolink($download['description']) : "&nbsp;" ?>
			</p>
		</div>
	</div>
<? } ?>

<? } ?>
