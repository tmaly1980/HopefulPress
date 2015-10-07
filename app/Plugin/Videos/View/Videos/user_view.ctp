<? $this->assign("admin_title", "View Video"); ?>
<?= $this->element("../Videos/view"); ?>

<script>
j('#Video_description_<?= $video['Video']['id'] ?>').inline_edit({link: "Add description/Edit description",rows: 8,type:'autogrow'});
</script>
