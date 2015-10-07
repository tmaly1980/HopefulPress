<div class='index'>
<ul>
<? foreach($sites as $site) {?>
<li><?= $this->Html->link($site['Site']['title'], array('controller'=>'homepages','action'=>'view','hostname'=>$site['Site']['hostname'])); ?>

<? } ?>
</ul>
</div>
