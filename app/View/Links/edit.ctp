<?= $this->element("Scraper.js"); ?>
<? $pid = Configure::read("project_id"); ?>

<? $id = !empty($this->request->data["Link"]["id"]) ? $this->request->data["Link"]["id"] : ""; ?>
<? $this->assign("page_title", $id ? "Edit Link Details" : "Add Link"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("admin_controls"); ?>
<? if(!empty($pid)) { ?>
	<?= $this->Html->blink("back", "View Project", "/project/$pid"); ?>
<? } else { ?>
	<?= $this->Html->blink("back", "View Links Page", "/links"); ?>
<? } ?>
<? $this->end(); ?>
<div class="links form col-md-6">
<?php echo $this->Form->create('Link'); ?>
	<?= $this->Form->input('id'); ?>
	<?= $this->Form->hidden('project_id'); ?>
	<?
		$waiting = $this->Html->image("/images/waiting.gif", array('style'=>'display:none;', 'id'=>'loadingTitle'));
		echo $this->Form->input('url',array('id'=>'LinkUrl', 'label'=>'Website Address (URL)','placeholder'=>'http://www.example.com/','size'=>50,'after'=>$waiting,'class'=>'required')); 
	?>
		<script>
		$('#LinkUrl').changeup(function() {
			var url = $('#LinkUrl').val();

			// only bother if valid looking url...
			if(!url.match(/\w+[.]\w+/))
			{
				return;
			}

			// fix http:// if not there.
			if(!url.match(/^\w+:\/\//))
			{
				url = "http://"+url;
				$('#LinkUrl').val(url);
			}

			//console.log("LOADLINKTITLE("+url+")="+j('#LinkTitle').val()+", HC="+j('#LinkTitle').hasClass('ghosting'));
			if(!url || $('#LinkTitle').val() != '' || $('#LinkTitle').hasClass('ghosting') || ($('#LinkTitle[placeholder]').size() && !$('#LinkTitle').val())) { return; }
			$('#loadingTitle').show();
			setTimeout("$('#loadingTitle').hide();", 10000);

			$.scraper(url, updateLinkTitle);
		}, 1500);
		
		function updateLinkTitle(json) // callback from scrape.
		{
			//console.log(json);
			//console.log("UPDATELINK TITLE");
			$('#loadingTitle').hide();
			//if(j('#LinkTitle').val() && j('#LinkTitle').hasClass('ghosting')) { return; }
		
			if(!json) { return; }
			//console.log(json);
			//j('#linkDetails').show();

			//j.modalcenter();

			// always clear title/description, since more sensible!
		
			if(json.title)// && !j('#LinkTitle').val())
			{
				$('#LinkTitle').val(json.title).change();
			}
			if(json.description)// && !j('#LinkDescription').val())
			{
				$('#LinkDescription').val(json.description).change();
			}
		}

		</script>
	<div id='linkDetails' >
	<?= $this->Form->input('title',array('id'=>'LinkTitle','size'=>50,'label'=>'Website Name','class'=>'required')); ?>
	<? if(empty($pid)) { ?>
	<div id="LinkCategory">
		<?= $this->requestAction("/link_categories/select",array('return')); ?>
	</div>
	<? } ?>
	<?= $this->Form->input('description',array('type'=>'textarea','cols'=>45,'rows'=>5,'class'=>'')); ?>
	<script>
		//j('#LinkDescription').maxlength();
	</script>
	</div>
	<?= $this->Form->save('Save Link');#, array('modal'=>true)); ?>
<?php echo $this->Form->end(); ?>
</div>
