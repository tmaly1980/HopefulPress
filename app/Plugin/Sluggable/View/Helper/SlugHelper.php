<?
class SlugHelper extends AppHelper
{
	var $helpers = array('Html','Form');

	# Admin interface related stuff...
	function slug($url = 'url', $opts = array())
	{
		# Assumes singular version of object type as prefix, ie /page, /news_post, /video, etc.
		$prefix = !empty($opts['prefix']) ? $opts['prefix'] : Inflector::underscore(Inflector::singularize($this->Form->defaultModel));

		# Specify /page, Imply /project/1/page, /members/page

		if(Configure::read("members_only")) { $prefix = "members/$prefix"; }
		if($pid = Configure::read("project_id")) { $prefix = "project/$pid/$prefix"; }

		if(isset($opts['prefix']) && $opts['prefix'] === false) { $prefix = null; } # NONE

		$title = !empty($opts['title']) ? $opts['title'] : 'title';
		$disabled = !empty($this->Form->request->data[$this->model()]['id']) ? "disabled" : "";
		ob_start();
		?>

		<?= $this->Form->input_group($url, array('before'=>$this->url("/".($prefix?"$prefix/":""), true), 'label'=>'Website Address','disabled'=>$disabled,
			'div'=>"paddingsides0",
			#'div'=>"col-lg-6 col-md-9 paddingsides0",
			'validate'=>true)); ?>

		<script>
		// XXX TODO we may have "Change" button and disabled field, so we at least can warn with "links will be broken" (if ID exists)
		// Then THAT field needs to be validated against!

		// Filter characters
		$('#<?= $this->Form->domId($url) ?>').filter_input({regex: '[^a-zA-Z0-9_]+', replace: '-'});

		// Altering title should query for slug, if not set.
		$('#<?= $this->Form->domId($title) ?>').change(function() {
			var form = $(this).closest('form');

			if($('#<?= $this->Form->domId($url) ?>').val()) { return; } // already set, won't change automatically.
			// XXX "reset" link?

			$('#<?= $this->Form->domId($url); ?>').loading(true);

			var formData = $(form).serializeObject();
			$.post("<?= $this->url(array('action'=>'slugify',$url)) ?>", formData, function(response) {
				$('#<?= $this->Form->domId($url); ?>').loading(false);
				if(response) {
					if(response.id) { $('#<?= $this->Form->domId('id'); ?>').val(response.id); }
					if(response.url && !$('#<?= $this->Form->domId($url) ?>').val()) { $('#<?= $this->Form->domId($url); ?>').val(response.url).change(); }
				}
			}, 'json');
		});

		/* Responsibility of modal
		$('#<?= $this->Form->domId("{$url}_fake") ?>').change(function() { // Keep in sync
			$('#<?= $this->Form->domId($url)?>').val(
				$('#<?= $this->Form->domId($url)?>').val()
			);
		});
		*/
		</script>
		<?
		return ob_get_clean();
	}


	function Xslugify($title = 'title', $url = 'url', $url_field = false)
	{
		if(!$this->Model->hasField('url')) { echo "<!-- no url field to slugify to -->"; return; }

		$titleField = $this->Model->fieldID($title);
		$titleValue = $this->Model->modelData($title);
		$urlField = $this->Model->fieldID($url);
		$urlValue = $this->Model->modelData($url); // Existing value
		$default = Router::url(array('admin'=>false, 'action'=>'v'), true);

		$value = !empty($urlValue) ? "$default/$urlValue" : null;


		# In case already set, we won't override unless we're told to.
		$recreate_link = $this->Hp->jslink("Update URL", "getSlug(true);",array('confirm'=>"By changing the URL, all links on other websites to this page will be broken and will need to be updated.",'confirm_yes'=>'OK'));

		ob_start();
		# on change title, submit title/id to slugify(), and get back url and id, if no id set yet.
		?>
		<script>
			function getSlug(force)
			{
				j('#slug').show();
				//console.log("waiting toggle1");
				j('.url_waiting').toggle();

				var formdata = { 
					data: { 
						<?= $this->Model->modelClass() ?>: { 
							id: j('#<?= $this->Model->fieldID('id') ?>').val(),
							title: j('#<?= $titleField ?>').val()
						},
						overwrite: force
					}
				};
				//j('#<?= $this->Model->formID(); ?>').serialize();
				j.post('<?= Router::url(array('admin'=>true,'action'=>'slugify')); ?>', formdata, function(response) {
					if(response) {
						if(response.id)
						{
							j('#<?= $this->Model->fieldID('id'); ?>').val(response.id);
						}
						if(response.url)
						{
							j('#<?= $this->Model->fieldID('url'); ?>').val(response.url);
							j('#url_full').val('<?= $default ?>/'+response.url);
						}

					}
					//console.log("waiting toggle3");
					j('.url_waiting').toggle();
				}, 'json');

			}
			// When title changes, ONLY if it WAS blank, do we update slug.
			j('#<?= $titleField ?>').change(function() { 
				if(!j('#<?= $this->Model->fieldID('url') ?>').val()) { getSlug(); } } 
			);
			// Only submit change to slug if url not already set.... otherwise wait until force update.

		</script>
		<div id="slug" class="form" style="<?= !$this->Model->modelData('url') ? "display: none;"  : "" ?>">
		<? if(!empty($url_field)) { # Else, already in page, inside form. ?>
			<?= $this->Form->hidden($url); ?>
		<? } ?>
		<?= $this->Form->input("{$url}_full", array('type'=>'textarea','id'=>'url_full','rows'=>2,'class'=>'slug url_waiting','cols'=>null,'readonly'=>true,'label'=>'Address (URL) for sharing '.$recreate_link,'value'=>$value, 'XXdefault'=>$default,'onClick'=>'j(this).select();','onBlur'=>'j(this).val(j(this).val())')); ?>
		<img class="url_waiting" src='/images/waiting.gif' style="display: none;"/>
		</div>
		<div class='clear'></div>
		<script>
		j(document).ready(function() { 
			if(j('#<?= $this->Model->fieldID('title') ?>').val())
			{
				if(!j('#<?= $this->Model->fieldID('title') ?>').hasClass('ghosting'))
				{
					if(!j('#<?= $this->Model->fieldID($url) ?>').val())
					{
						getSlug(true); // blank, force creation for sanity.
					} else {
						j('#slug').show(); // so reloads don't accidentally hide, since default is to be not visible.
						//j('#slug').hp_autogrow(); // 
					}
				}
			}
		});
		</script>
		<?
		return ob_get_clean();
	}

}
