<? $this->assign("page_title", "Add a Video"); ?>
<? $models = array_keys($this->request->models); $modelClass = $models[0]; ?>
<div class="form" style="width: 700px;">

<? if(empty($this->data[$modelClass]['video_url'])) { # Hide unless really needed. Must be full thing, else page_id will screw up ?>
	<?= $this->element("Videos.../Videos/user_add"); ?>
<?  } ?>


<textarea style="display: none; width: 100%;" rows=10 id="raw"></textarea>

<div id="video_details">
	<?= $this->element("Videos.../Videos/user_details"); ?>
</div>

</div>

</div>
