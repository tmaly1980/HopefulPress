<? $this->set("back_title", "Back to album"); ?>
<? $this->set("back_url", array('controller'=>'photo_albums','action'=>'view',$photo['Photo']['photo_album_id'],'project_id'=>$photo['Photo']['project_id'])); ?>
<? $this->set("edit_title", false); ?>
<? $this->set("ownable", false); ?>
<?= $this->element("../Photos/view"); ?>

<script>
j('#Photo_caption_<?= $photo['Photo']['id'] ?>').inline_edit({link: "Add caption/Edit caption",rows: 4,type:'autogrow'});
</script>
