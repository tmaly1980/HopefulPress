<?

class PublishableHelper extends AppHelper
{ # stuff relating to publishing.
	var $helpers = array('Form','Html','Site');

	function is_published() # For current record.
	{
		return !$this->Form->hasField("published") || 
			$this->Form->fieldValue("published");
	}

	function unpublishedWarn($records = null, $things = null)
	{
		if(!Configure::read("in_admin")) { return; } # Irrelevant.
		if(empty($records)) { $records = $this->Form->data; }
		$Model = $this->Form->model();
		$unpublished = Set::extract("/{$Model}[published=]", $records);

		if(count($unpublished))
		{
			ob_start();
			?>
			<div class='margintop20 tip'>Remember to publish your <?= !empty($things) ? $things : $this->Form->things(); ?> to make them viewable by others</div>
			<?
			return ob_get_clean();
		}
		# Else, say nothing.
	}

	# Gets put before public page header/nav.
	function publish() # Caller should restrict to admin_view()
	{
		if(!$this->Form->hasField("published")) { error_log("HELPER MODEL HAS NO FIELD 'published'"); return; }

		$published = $this->Form->fieldValue("published");
		$thing = $this->Form->thing();
		$id = $this->Form->id();
		$draft_id = $this->Form->fieldValue('draft_id');

		ob_start();
		?>
		<div id='Publishable' class='medium padding15 borderbottom5 <?= $published ? "lightgreybg" : "lightyellowbg" ?>'>
			<? if(!$published && !empty($draft_id)) { # More useful/understandable. ?>
				This <?= $thing ?> has been changed, but the changes are not yet published.
				<? if($this->Site->can('edit')) { ?>
				<?= $this->Html->link("Edit this $thing", array('action'=>'edit', $id), array('class'=>'blue color bold uppercase underline')); ?> to make further changes.
				<br/><br/><?= $this->Html->link("Publish the changes", array('action'=>'publish', $id), array('class'=>'green color bold uppercase underline')); ?> so everybody can see them.
				<?= $this->Html->link("Erase the changes", array('action'=>'delete', $id), array('class'=>'red color bold uppercase underline')); ?> to restore the original <?= $thing ?>.
				<? } ?>

			<? } else if($published) { ?>
				This <?= $thing ?> is currently <b class='uppercase'>published</b> and visible to <?= $this->Admin && $this->Admin->members_only() ? "members" : "the public" ?>. 
				<? if($this->can('edit')) { ?>
				<?= $this->Html->link("Unpublish this $thing", array('action'=>'unpublish', $id), array('confirm'=>"Are you sure you want to unpublish this page? Nobody will be able to see it until it's republished", 'class'=>'red color bold uppercase underline')); ?> to hide it.
				<? } ?>
			<? } else { ?>
				Almost done! This <?= $thing ?> is <b class='uppercase'>not yet published</b> or visible to <?= $this->Admin && $this->Admin->members_only() ? "members" : "the public" ?>. 
				<? if($this->Site->can('edit')) { ?>
				<?= $this->Html->link("Edit this $thing", array('action'=>'edit', $id), array('class'=>'blue color bold uppercase underline')); ?> to make further changes.
				<br/><br/><?= $this->Html->link("Publish this $thing", array('action'=>'publish', $id), array('class'=>'bold green color bold uppercase underline')); ?> so everybody can see it.
				<? } else { ?>
				<i>Only the owner or an administrator can publish this <?= $thing ?>.</i>
				<? } ?>
			<? } ?>
		</div>
		<?
		return ob_get_clean();
	}

	function link($data = null, $modelClass = null) # Prefers root data, not just model class, in case of drafting access.
	{
		if(empty($data)) { $data = $this->Form->data; } # Default onto sole record, if applicable.
		if(empty($modelClass)) { $modelClass = $this->Form->model(); }
		$mc = $modelClass;
		$mdata = isset($data[$mc]) ? $data[$mc] : $data;
		$draft = !empty($data['Draft']['idurl']) ? $data['Draft'] : $data[$mc];

		$id = !empty($mdata['idurl']) ? $mdata['idurl'] : $mdata['id'];
		$thing = $this->Form->thing($mc);
		$title = !empty($draft['title']) ? $draft['title'] : "<i>Untitled ".ucwords($thing)."</i>";
		$project_id = !empty($mdata['project_id']) ? $mdata['project_id'] : null;

		$controller = Inflector::tableize($modelClass);

		$unpublished = $this->isUnpublished($data,$modelClass);

		return $this->Html->link($title, array('controller'=>$controller,'action'=>'view',$id,'project_id'=>$project_id), array('class'=>'title')).$this->admin_status($data,$modelClass);
	}

	function admin_status($data = null, $modelClass = null) # For admin index titles.
	{
		if(!Configure::read("in_admin")) { return; } # Only admin.
		#print_r($data);

		if(!$this->Form->hasField("published")) { return; }

		if(empty($modelClass)) { $modelClass = $this->Form->model(); }

		if(empty($data)) { $data = $this->Form->data; } # always generalize, so can be use for drafting. #Model->modelData(); } # Default onto sole record, if applicable.
		$mdata = !empty($data[$modelClass]) ? $data[$modelClass] : $data; # Exaggerate so we can see there's an error.


		if($this->isDraft($data,$modelClass))
		{
			return " <b class='bold red'> &ndash; MODIFIED</b>";
		} else if ($this->isUnpublished($data,$modelClass)) { 
			return " <b class='bold red'> &ndash; UNPUBLISHED</b>";
		}
	}

	function isDraft($data,$modelClass = null)
	{
		if(empty($data)) { $data = $this->Form->data; } # Default onto sole record, if applicable.
		if(empty($data)) { return false; } # Index, etc, not applicable.
		if(empty($modelClass)) { $modelClass = $this->Form->model(); }
		#$ddata = isset($data["Draft$modelClass"]) ? $data["Draft$modelClass"] : $data;
		$ddata = isset($data["Draft"]) ? $data["Draft"] : $data;

		#print_r($data);

		return !empty($ddata['id']);
	}

	function isUnpublished($data,$modelClass = null)
	{
		if(empty($data)) { $data = $this->Form->data; } # Default onto sole record, if applicable.
		if(empty($data)) { return false; } # Index, etc, not applicable.
		if(empty($modelClass)) { $modelClass = $this->Form->model(); }
		if(isset($data[$modelClass])) { $data = $data[$modelClass]; }

		return (array_key_exists('published', $data) && empty($data["published"]));
	}

	function admin_filters()
	{
		if(!Configure::read("in_admin")) { return; } # Only admin.
		
		# Depends on 'statusAllCount', 'statusPublishedCount', and 'statusUnpublishedCount'
		$all = $this->getVar("statusAllCount");
		$published = $this->getVar("statusPublishedCount");
		$unpublished = $this->getVar("statusUnpublishedCount");

		ob_start();
		?>
		<div align='right' class='publish_filters'>
			<? if(!empty($all) && (!empty($published) || !empty($unpublished))) { # ONly bother when other options. ?>
				<?= $this->Html->link("All", array('action'=>'index'), array('class'=>'','activeoff'=>1)); ?> (<?= $all ?>)
			<? } ?>
			<? if(!empty($published)) { ?>
				|
				<?= $this->Html->link("Published", array('action'=>'index','published'=>1), array('class'=>'','activeoff'=>1)); ?> (<?= $published ?>) 
			<? } ?>
			<? if(!empty($unpublished)) { ?>
				|
				<?= $this->Html->link("Unpublished", array('action'=>'index','published'=>0), array('class'=>'','activeoff'=>1)); ?> (<?= $unpublished ?>) 
			<? } ?>
		</div>
		<?
		return ob_get_clean();
	}

	###################
	# Autosaving

	function autosave()
	{
		ob_start();
		?>
		<?= $this->Form->hidden("draft_id"); ?>
		<div id='docstatus'></div>

		<script>
		var form = $('#<?= $this->Form->domId('draft_id')?>').closest('form');

		$(document).ready(function() {
			console.log("saving form state");
			$(form).data('serialized', $(form).serialize()); // save initial state

			// Once keydown, trigger dirty
			$(form).find(':input').on('keypress change', function() {
				clearInterval($(form).data('autosave_timer_id'));

				console.log("keypress init autosave");
				// Call every 30 seconds
				var secs = 15;
				$(form).data('autosave_timer_id', setInterval(function() { $(form).autosave(); }, secs*1000));
	
			});
		});

		// Dont bother autosaving until title started to be filled out
		$('#<?= $this->Form->domId('title') ?>').change(function() {

			if(!$(this).val()) { 
				// Stop timer
				if(timer_id = $(form).data('autosave_timer_id'))
				{
					console.log("Stopping timer, title is empty");
					clearInterval($(form).data('autosave_timer_id'));
					$(form).data('autosave_timer_id',null);
				}
				return; 
			} // irrelevent
			console.log("Starting autosave timer...");

			// Call every 30 seconds
			$(form).data('autosave_timer_id', setInterval(function() { $(form).autosave(); }, 30*1000));

			// Call immediately as well
			$(form).autosave();
		});

		$.fn.autosave = function(dirty) {
			var form = this;

			console.log("autosave trigger...");

			if(!dirty && $(form).serialize() === $(form).data('serialized')) { 
				console.log("CLEAN");
				return; } // NOT DIRTY (no changes)

			$('#docstatus').show().html('Saving Draft...');

			$(form).find('[type=submit]').attr('disabled','disabled'); 

			$.post("<?= $this->url(array('action'=>'autosave')); ?>", $(form).serializeObject(), function(response) {
				console.log("autosave responded...");
				if(!response) { return; }
				console.log(response);

				// Update ID
				var idField = $('#<?= $this->Form->domId('id'); ?>');
				var draftIdField = $('#<?= $this->Form->domId('draft_id'); ?>');

				if(response.id) { idField.val(response.id); }
				if(response.draft_id) { draftIdField.val(response.draft_id); }
				if(response.docstatus) { $('#docstatus').html(response.docstatus); }

				$(form).find('[type=submit]').removeAttr('disabled');

				// Save state for dirty check
				$(form).data('serialized', $(form).serialize());

			},'json');
		}
		</script>
		<?
		return ob_get_clean();

	}

}
