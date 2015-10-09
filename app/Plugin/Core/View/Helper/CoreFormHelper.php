<?
App::uses('BoostCakeFormHelper','BoostCake.View/Helper');
class CoreFormHelper extends BoostCakeFormHelper 
{
	var $helpers = array('Session','Text','Html');

	var $formID = null;
	var $validateFields = array();
	var $validate = true;

	var $yesnobool = array(0=>'No',1=>'Yes');

	# No difference between blanks and 0, so must use words.
	var $yesno = array('No'=>'No','No'=>'Yes');
	var $yesnona = array(''=>'- N/A -','No'=>'No',"Yes"=>'Yes');
	var $yesnoblank = array(''=>' - ','No'=>'No','Yes'=>'Yes');

	public $months = array(
			'01'=>'01 - January',
			'02'=>'02 - February',
			'03'=>'03 - March',
			'04'=>'04 - April',
			'05'=>'05 - May',
			'06'=>'06 - June',
			'07'=>'07 - July',
			'08'=>'08 - August',
			'09'=>'09 - September',
			'10'=>'10 - October',
			'11'=>'11 - November',
			'12'=>'12 - December',
	);
	public $states = array(
	    'AL'=>'Alabama',
	    'AK'=>'Alaska',
	    'AZ'=>'Arizona',
	    'AR'=>'Arkansas',
	    'CA'=>'California',
	    'CO'=>'Colorado',
	    'CT'=>'Connecticut',
	    'DE'=>'Delaware',
	    'DC'=>'District of Columbia',
	    'FL'=>'Florida',
	    'GA'=>'Georgia',
	    'HI'=>'Hawaii',
	    'ID'=>'Idaho',
	    'IL'=>'Illinois',
	    'IN'=>'Indiana',
	    'IA'=>'Iowa',
	    'KS'=>'Kansas',
	    'KY'=>'Kentucky',
	    'LA'=>'Louisiana',
	    'ME'=>'Maine',
	    'MD'=>'Maryland',
	    'MA'=>'Massachusetts',
	    'MI'=>'Michigan',
	    'MN'=>'Minnesota',
	    'MS'=>'Mississippi',
	    'MO'=>'Missouri',
	    'MT'=>'Montana',
	    'NE'=>'Nebraska',
	    'NV'=>'Nevada',
	    'NH'=>'New Hampshire',
	    'NJ'=>'New Jersey',
	    'NM'=>'New Mexico',
	    'NY'=>'New York',
	    'NC'=>'North Carolina',
	    'ND'=>'North Dakota',
	    'OH'=>'Ohio',
	    'OK'=>'Oklahoma',
	    'OR'=>'Oregon',
	    'PA'=>'Pennsylvania',
	    'RI'=>'Rhode Island',
	    'SC'=>'South Carolina',
	    'SD'=>'South Dakota',
	    'TN'=>'Tennessee',
	    'TX'=>'Texas',
	    'UT'=>'Utah',
	    'VT'=>'Vermont',
	    'VA'=>'Virginia',
	    'WA'=>'Washington',
	    'WV'=>'West Virginia',
	    'WI'=>'Wisconsin',
	    'WY'=>'Wyoming',
	);

	function input($fieldFull, $attrs = array()) # Allows 'div'=>'classnames' and label=>'classnames' shorthand
	{
		$this->setEntity($fieldFull);
		$magicOptions = $this->_magicOptions($attrs); # Detect/guess type.
		if(!isset($attrs['type'])) {  # Otherwise we may have been explicit and internally thinks differently (file vs text)
			$attrs['type'] = $magicOptions['type']; 
		}

		# Initialize div structure
		if(!isset($attrs['div']))
		{
			$attrs['div'] = array('class'=>'');
		} else if (is_array($attrs['div'])) { 
			if(!isset($attrs['div']['class']))
			{
				$attrs['div']['class'] = '';
			}
		} else if (is_string($attrs['div'])) { 
			$attrs['div'] = array(
				'class'=>$attrs['div']
			);
		} # Might be disabled too

		if(!empty($attrs['div']))
		{
			$attrs['div']['class'] .= ' form-group';
		}

		if(!isset($attrs['class'])) { $attrs['class'] = ""; }
		if(!isset($attrs['script'])) { $attrs['script'] = ""; }
		if(!isset($attrs['id'])) { 
			$attrs['id'] = $this->domId($fieldFull);#"input".rand(10000,99999); 
		}

		if(!empty($attrs['required']) && !empty($attrs['div'])) # Correct div class (ie no validation  in model)
		{
			$attrs['div']['class'] .= ' required';
		}

		if($attrs['type'] == 'checkbox' || $attrs['type'] == 'radio') # Bootstrap fix.
		{
			#$attrs['div'] = false; # Already does it
			#$attrs['format'] = array('before','label','input','between','after','error');
		} else {
			$attrs['class'] .= ' form-control';
		}

		# Get multi-checkbox working
		if($attrs['type'] == 'checkbox' && !empty($attrs['options']))
		{
			# FIX to add div checkbox
			/*
			if(!isset($attrs['div'])) { 
				$attrs['div'] = 'checkbox';
			} else if (is_string($attrs['div'])) { 
				$attrs['div'] .= ' checkbox';
			} else if (is_array($attrs['div'])) { 
				if(!isset($attrs['div']['class']))
				{
					$attrs['div']['class'] = 'checkbox';
				} else {
					$attrs['div']['class'] .= ' checkbox';
				}
			}
			*/

			$attrs['type'] = 'select';
			$attrs['multiple'] = 'checkbox';
			if(!empty($attrs['label'])) {  $attrs['legend'] =$attrs['label']; $attrs['label'] = false; }
			if(!empty($attrs['legend']))
			{
				$attrs['label'] = false;
				$attrs['before'] = "<fieldset><legend>{$attrs['legend']}</legend>";
				$attrs['after'] = "</fieldset>";
			}
		}

		# Allow KEY.SUBKEY shortcuts.
		#$attrs = $this->dotconvert($attrs); # TODO

		if(!empty($attrs['date']))
		{
			$attrs['class'] .= ' datepicker';
			$attrs['type'] = 'text';
			$attrs['size'] = 10;
		}

		list($model,$field) = pluginSplit($fieldFull);
		if(empty($model)) { $model = $this->defaultModel; }

		if(!empty($attrs['validate']))
		{
			$this->validateFields["$model.$field"] = $attrs['validate']; # User.whatever format
		}

		if(!isset($attrs['data-bv-stringlength']) && !isset($attrs['data-bv-minlength']) && !isset($attrs['data-bv-minlength']))
		{
			$attrs['data-bv-stringlength'] = 'false'; # Don't implement maxlength validators unless explicit
		}

		if(!empty($attrs['note'])) { $attrs['tip'] = $attrs['note']; }
		if(!empty($attrs['note_right'])) { $attrs['tip_right'] = $attrs['note_right']; }

		if(!empty($attrs['tip_right']))
		{
			if(!isset($attrs['after'])) { $attrs['after'] = ''; }
			$attrs['after'] .= "<div class='tip' align='right'>{$attrs['tip_right']}</div>";
		}
		if(!empty($attrs['tip']))
		{
			if(!isset($attrs['after'])) { $attrs['after'] = ''; }
			$attrs['after'] .= "<div class='tip'>{$attrs['tip']}</div>";
		}

		$script = '';

		if(!empty($attrs['help']))
		{
			$script .= "<script>$('#{$attrs['id']}').addHelp('#{$attrs['help']}');</script>";
		}


		if(!empty($attrs['script']))
		{
			$script = "<script>\n".$attrs['script']."\n</script>";
			unset($attrs['script']);
		}

		if(!empty($attrs['label_alt']))
		{
			if(!isset($attrs['between']))
			{
				$attrs['between'] = '';
			}
			$attrs['between'] .= $attrs['label_alt'];
			unset($attrs['label_alt']);
		}


		$output = parent::input($fieldFull, $attrs).$script;

		/*
		echo "DIV=".print_r($attrs['div'],true);
		echo  "TYPE=".print_r($attrs['type'],true);

		# XXX TODO add 'div' support for $attrs['type'] = radio
		if(!empty($attrs['div']) && in_array($attrs['type'], array('radio','checkbox')))
		{
			$divclass = "";

			if (is_string($attrs['div']) && !empty($attrs['div'])) { 
				$divclass = $attrs['div'];
			} else if(is_array($attrs['div']) && !empty($attrs['div']['class'])) {
				$divclass = $attrs['div']['class'];
			}

			$output = "<div class='$divclass'>$output</div>";
		}
		*/

		return $output;
	}


	function input_group($field, $opts = array())
	{
		if(!isset($opts['beforeInput'])) { $opts['beforeInput'] = ''; }
		if(!isset($opts['beforeInput'])) { $opts['beforeInput'] = ""; }

		if(empty($opts['wrapInput'])) { $opts['wrapInput'] = ''; }
		$opts['wrapInput'] .= ' input-group';

		$class = '';

		if(!empty($opts['after_icon']))
		{
			$class = "glyphicon glyphicon-{$opts['after_icon']}";
			$opts['after'] = '';
		}

		if(!empty($opts['before_icon']))
		{
			$class = "glyphicon glyphicon-{$opts['before_icon']}";
			$opts['before'] = '';
		}

		if(isset($opts['after']))
		{
			$opts['afterInput'] = "<span class='input-group-addon $class'>".$opts['after']."</span>";
			unset($opts['after']);
		} else if (isset($opts['before'])) { 
			$opts['beforeInput'] .= "<span class='input-group-addon $class'>".$opts['before']."</span>";
			unset($opts['before']);
		}

		return $this->input($field, $opts);
	}

	function save($label = 'Save', $opts=array())
	{
		if(empty($opts['class'])) { $opts['class'] = ''; }
		if(!isset($opts['div'])) { $opts['div'] = ''; }
		$opts['class'] .= ' btn save';
		if(!preg_match("/btn-/", $opts['class'])) { $opts['class'] .= ' btn-success'; }
		$opts['escape'] = false;
		if(!isset($opts['cancel']) && !$this->id()) { $opts['cancel'] = false; } # DOnt show cancel on new records.
		$icon = "<span class='glyphicon glyphicon-play'></span> ";
		return ($opts['div'] !== false ? "<div class='{$opts['div']}'>":"").
			$this->button("$label $icon", $opts).
			#((!isset($opts['cancel']) || $opts['cancel'] !== false) ?
			(!isset($opts['cancel']) || !empty($opts['cancel']) ? 
				" or ".$this->cancel($opts) : "").
			($opts['div'] !== false ? "</div>" : "");
	}

	function cancel($options = array())
	{
		$controller = $this->request->params['controller'];
		if(!empty($options['cancel_js'])) 
		{
			$options['cancel'] = 'javascript:void(0)';
			$options['cancel_onclick'] = $options['cancel_js'];
		} else if(!empty($options['cancel']) && !empty($options['modal'])) {  # deal via json
			$options['json'] = 1;
		} else if(!empty($options['modal'])) {
			$options['cancel'] = 'javascript:void(0)';
			$options['cancel_onclick'] = '$.dialogclose();';
		} else if(empty($options['cancel'])) {
			# FOR NOW LETS ASSUME VIEW PAGE IS PUBLIC...
			$options['cancel'] = array('prefix'=>false,'controller'=>$controller);
			$options['cancel']['action'] = 'index'; # If singleton, index should redirect to view
		}

		if(!empty($options['cancel_confirm']))
		{
			# TODO may need to integrate existing onclick if confirm isnt good enough.
			#$options['cancel_onclick'] = "return confirm('{$options['cancel_confirm']}');";
			$options['cancel_onclick'] = "$.confirm('{$options['cancel_confirm']}', {event: event});";
			# XXX BROKEN!!!!
		}
		$update = !empty($options['update']) ? $options['update'] : null;

		return $this->Html->link((!empty($options['cancel_title']) ? $options['cancel_title'] : "Cancel"), $options['cancel'], array('class'=>'red color cancel','update'=>$update,'onClick'=>(!empty($options['cancel_onclick']) ? $options['cancel_onclick'] : null) )); 
	}

	function create($model = null, $opts = array(), $attrs = array())
	{
		$this->validateFields = array(); # Reset.

		$this->validate = isset($opts['validate']) ? $opts['validate'] : 
			(isset($attrs['validate']) ? $attrs['validate'] : true);

		if(!isset($opts['class'])) { $opts['class'] = ''; }

		if(!empty($opts['json']))
		{
			$opts['class'] .= ' json';
		}

		if(!empty($opts['update'])) # Convenience
		{
			$opts['class'] .= ' ajax'; # Might be json, maybe html
			$opts['data-update'] = $opts['update'];
		}

		if(!isset($opts['inputDefaults']))
		{
			$opts['inputDefaults'] = array(
				'format'=>array('before','label','between','input','error','after'),
				'div'=>'form-group',
				'wrapInput'=>false,
				#'class'=>'form-control', # Done in input()
				'error'=>array('attributes'=>array('wrap'=>'span','class'=>'help-inline'))
			);
		}

		$form = parent::create($model, $opts, $attrs);
		$domId = isset($opts['action']) ? $opts['action'] : $this->request['action'];
		$this->formID = !empty($opts['id']) ? $opts['id'] : $this->domId($domId . 'Form');

		return $form;
	}

	function end($options = null, $secureAttrs = array())
	{
		return parent::end($options, $secureAttrs).$this->validate();
	}

	function validate()
	{
		ob_start();
		if(!empty($this->validate)) { #true || !empty($this->validateFields)) {  # ALWAYS NOW
		?>
		<script>
		$(document).ready(function() {
			$('#<?= $this->formID ?>').bootstrapValidator({
				excluded: ':disabled',//':disabled',':not(:visible)'], // need to not have :hidden here, since hidden fields may be used with UI hacks
				message: 'Please provide valid information',
				trigger: 'keyup change', //  fixes datepicker etc
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-cog spinner',
				},
				fields: { /* might be empty, if we directly use data-bv-* */
					<? $i = 0; foreach($this->validateFields as $fieldName=>$rule) { 
						list($model,$field) = pluginSplit($fieldName);
						if(empty($model)) { $model = $this->defaultModel; }
					?>
					"data[<?= $model ?>][<?= $field ?>]": {
						validators: {
							<? if($rule === true) { # AJAX ?>
							remote: {
								url: '/<?= !empty($this->request->params['plugin']) ? $this->request->params['plugin'].'/':"" ?><?= $this->request->params['controller'] ?>/validateField/<?= $model ?>/<?= $field ?>',
								type: 'POST',
								data: $('#<?= $this->formID ?>').serializeObject(),
								delay: 1000
							}
							<? } else { # notEmpty, date, emailAddress, integer, numeric, # OTHERWISE we can use data-bv-* in input() ?>
							<?= $rule ?>: {
							}
							<? } ?>
						}
					}<?= ++$i < count($this->validateFields) ? "," : "" ?>
					<? } ?>
				}
			}).on('success.form.bv', function(e) {
				var form = $('#<?= $this->formID ?>');
				if(form.is('.json, .ajax'))
				{
					e.preventDefault(); // so not submitted twice (doesnt think submit will do it)
					// ajax submit may need to be called from here....
					// but how do we stop the default handler? by checking for bv-form and using a different event triggered.
					form.trigger('ajaxSubmit');
				}
			});
		});
		</script>
		<?
		}

		return ob_get_clean();
	}

	function daterangepicker($field, $opts = array())
	{
		$opts['div'] = false;
		if(empty($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= 'width300';
		if(empty($opts['id'])) { $opts['id'] = 'daterange-'.rand(10000,99999); }
		ob_start();
		?>
                 <fieldset>
                  <div class="control-group">
                    <div class="controls">
                     <div class="input-prepend input-group">
                       <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
		       <?= $this->text($field, $opts); ?>
                     </div>
                    </div>
                  </div>
                 </fieldset>
		 <script>
		 	$('#<?= $opts['id'] ?>').daterangepicker({
			      ranges: {
			         'Today': [moment(), moment()],
			         'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
			         'Last 7 Days': [moment().subtract('days', 6), moment()],
			         'Last 30 Days': [moment().subtract('days', 29), moment()],
			         'This Month': [moment().startOf('month'), moment().endOf('month')],
			         'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
			      },
		 	});
		 </script>

		<?
		return ob_get_clean();
	}

	####################
	# Model/data helpers
	function hasField($field)
	{
		return $this->_introspectModel($this->model(), 'fields', $field);
	}

	function data()
	{
		if(!empty($this->request->data))
		{
			return $this->request->data;
		} else { # singleVar, ie newsPost
			return $this->getVar($this->singleVar());
		}
	}

	function fieldValue($field)
	{
		$data = $this->data();
		list($model,$field) = pluginSplit($field);
		if(empty($model)) { $model = $this->model(); }

		#echo("GETTING $model/$field=".print_r($data,true));

		return isset($data[$model][$field]) ?
			$data[$model][$field] : null;
	}

	function id() { return $this->fieldValue('id'); }
	#function title() { return $this->fieldValue('title'); }

	function title($field = 'title', $opts = array())
	{
		if(!isset($opts['placeholder'])) { $opts['placeholder'] = 'Add a title...'; }
		$opts['label'] = false;
		if(!isset($opts['class'])) { $opts['class'] = ''; }
		$opts['class'] .= ' input-lg bold';
		#if(!isset($opts['div'])) { $opts['div'] = ' col-md-9'; }
		return $this->input($field, $opts);
	}

	function content($field = 'content', $attrs = array())
	{
		if(!isset($attrs['placeholder'])) { $attrs['placeholder'] = 'Add some content....';; }
		if(!isset($attrs['label'])) { $attrs['label'] = false;  }
		if(!isset($attrs['class'])) { $attrs['class'] = '';  }
		$attrs['class'] = ' editor';
		return $this->input($field, $attrs);
	}


	#################
	# Var names

	function singleVar($model = null) # newsPost
	{
		if(empty($model)) { $model = $this->model(); }
		return Inflector::singularize(Inflector::variable($model));
	}

	function pluralVar($model = null)
	{
		return Inflector::pluralize($this->singleVar($model));
	}

	function ucThing($model = null) # News Post
	{
		return ucwords($this->thing($model));
	}

	function thing($model = null) # news post
	{
		if(empty($model)) { $model = $this->model(); }
		return strtolower(Inflector::singularize(Inflector::humanize($model)));
	}
	function things($model = null) # news posts
	{
		return Inflector::pluralize($this->thing($model));
	}
	function ucThings($model = null) # News Posts
	{
		return Inflector::pluralize($this->ucThing($model));
	}

	function model() # Can be used outside of forms (view/index)
	{
		# This is unreliable if previous inputs had a different model class.
		#$model = parent::model();
		$model = $this->defaultModel; # Better!

		if(empty($model)) # Determine from controller
		{
			$model = Inflector::classify(Inflector::singularize($this->request->params['controller']));
		}
		return $model;
	}

	###########################
	# Inline edit, similar to how input() works
	function editable($data, $fieldName, $opts =array())
	{
		ob_start();

		list($model,$field) = pluginSplit($fieldName);
		if(strpos($fieldName, '.') === false) { $model = $this->model(); }

		# Need to set Model so domId reflects that

		$id = !empty($opts['id']) ? $opts['id'] : "{$model}_$field";

		$primaryKey = $this->_introspectModel($model, 'key');

		$pk = !empty($data[$model][$primaryKey]) ? $data[$model][$primaryKey] : ""; 

		$value = $data[$model][$field];

		# figure out type of input based upon field schema
		$schema = $this->_introspectModel($model, 'fields', $field);
		$stype = $schema['type'];

		$map = array(
                	'string' => 'text', 'datetime' => 'datetime',
                        'boolean' => 'checkbox', 'timestamp' => 'datetime',
                        'text' => 'textarea', 'time' => 'time',
                        'date' => 'date', 'float' => 'number',
                        'integer' => 'number', 'decimal' => 'number',
                        'binary' => 'file'
                );

		$type = $map[$stype];

		$tag = !empty($opts['tag']) ? $opts['tag'] : 'p';

		$class = !empty($opts['class']) ? $opts['class'] : "";

		#$inputclass = !empty($opts['inputclass']) ? $opts['inputclass'] : '';

		$formclass = !empty($opts['formclass']) ? $opts['formclass'] : null;

		$title = "Edit $field";

		?>
		<button id="<?= $id ?>_button" type="button" class="btn block left margin5 btn-sm btn-primary"><span class='glyphicon glyphicon-pencil' title='<?= $title ?>'></span></button>
		<<?=$tag ?> id="<?= $id ?>" class="<?= $class ?>"><?= $value ?></<?= $tag?>>
		<div class='clear'></div>
		<script>
		$('#<?= $id ?>').on('shown', function(e, editable) {
			$('#<?= $id ?>_button').hide();
			
			var form = $(editable.input.$input).closest('form');
			console.log("FORM=");
			console.log(form);
			<? if(!empty($formclass)) { ?>
			form.removeClass('form-inline').addClass('<?= $formclass ?> ');
			<? } ?>

		}).on('hidden', function(e,editable) { 
			$('#<?= $id ?>_button').show();
		
		
		}).editable({
			pk: '<?= $pk ?>',
			url: '<?= $this->url(array('action'=>'editable')); ?>',
			mode: 'inline',
			name: '<?= $model ?>.<?= $field ?>',
			title: '<?= $title ?>',
			type: '<?= $type ?>',
			inputclass: '<?= !empty($class) ? $class : "" ?> ',
			showbuttons: '<?= $type == 'textarea' ? 'bottom' : 'left' ?>',
			unsavedclass: null
		}); 
		$('#<?= $id ?> button.btn').click(function() {

		});
		</script>
		<?
		return ob_get_clean();
	}

	function fileupload($name, $label, $attrs = array()) 
	{
		$id = !empty($attrs['id']) ? $attrs['id'] : 'fileupload';
		ob_start();
		?>
		<span class='btn btn-success fileinput-button'>
			<i class='glyphicon glyphicon-plus'></i>
			<span><?= $label ?></span>
			<?= $this->input($name, array('type'=>'file','multiple'=>'multiple', 'id'=>$id,'label'=>false,'div'=>false)); ?>
		</span>
		<?
		return ob_get_clean();
	}

	function time($field, $opts = array())
	{
		$options = array();
		$hour = 0;$min = 0;
		for($hour = 0; $hour < 24; $hour++)
		{
			for($min = 0; $min < 60; $min += 15)
			{
				$time = sprintf("%02u:%02u:00", $hour, $min);
				$hour24 = $hour % 12;
				$time24hr = sprintf("%02u:%02u%s", $hour24?$hour24:12, $min, $hour > $hour24 ? "pm":"am");
				$options[$time] = $time24hr;
			}
		}

		$model = $this->defaultModel;

		$id = Inflector::camelize($model."_".$field);

		return $this->input($field, array_merge(array(
			'options'=>$options,
			'empty'=>'',
			'default'=>'12:00:00',
			'id'=>$id,
		), $opts));
	}

	function date($field, $opts = array())
	{
		$options = array();

		$div = array( # Needs to be in div so add-on included
			'class'=>'date',
			'data-provide'=>'datepicker',
			'data-date-format'=>'mm/dd/yyyy',
		);
		if(!empty($opts['multidate'])) { $div['data-date-multidate'] = 'true'; }
		else { $div['data-date-autoclose'] = 1; }

		return $this->input_group($field, array_merge(array(
			'type'=>'text','size'=>10,
			'div'=>$div,
			'placeholder'=>'mm/dd/yyyy',
			'after_icon'=>"calendar"
		),$opts));
	}

	var $paletteOLD = array(
	 	array("#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"),
	        array("#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"),
	        array("#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"),
	        array("#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"),
	        array("#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"),
	        array("#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"),
	        array("#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"),
	        array("#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130")
	);

	var $palette = array( # Revised, more sensible colors. ONLY Needs to be darker/primary colors
	 	#"#000",
		"#444","#666","#999","#ccc",
		#"#eee","#f3f3f3","#fff",
	        #"#f00",
		"#f90",
		#"#ff0", "#0f0","#0ff","#00f",
		"#90f",
		#"#f0f",
	        #"#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc",
	        #"#ea9999", "#f9cb9c",
		#"#ffe599",
		"#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd",
	        "#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0",
	        "#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79",
	        "#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47",
	        "#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"
	);

	function color($field)
	{
		ob_start();
		$domId = $this->domId($field);
		echo $this->hidden($field);
		$selected = $this->fieldValue($field);

		?>
		<div id='<?= $domId ?>_palette' class='row palette'>
		<?
		foreach($this->palette as $hex)
		{
			$color = substr($hex,1);
			echo $this->Html->link("&nbsp;", "#", array('data-color'=>$color,'class'=>"color ".(strtolower($selected)==strtolower($color)?" active":""),'style'=>"background-color: #$color;",'title'=>strtoupper("#$color"))); 
		}
		?>
		</div>

		<script>
		$('#<?= $domId ?>_palette a').click(function(e) {
			e.preventDefault();
			$('#<?= $domId ?>_palette a').removeClass('active');
			$(this).addClass('active');
			$('#<?= $domId ?>').val($(this).data('color')).change();
		});
		</script>
		<style>
			.palette a.color
			{
				display: block;
				float: left;
				width: 48px;
				height: 48px;
				margin: 2px;
				border: solid white 5px;
				border-radius: 5px;
			}
			.palette a.color.active
			{
				border-color: green;
			}
			.palette a.color:hover,
			.palette a.color:active,
			.palette a.color:visited
			{
				text-decoration: none;
			}

		</style>
		<?
		return ob_get_clean();
	}

	function thumbs($field, $opts=array()) # Thumbnail images (100x100) click for specific (hidden) options.
	{
		ob_start();
		$domId = $this->domId($field);
		echo $this->hidden($field, $opts);
		$selected = $this->fieldValue($field);

		list($model,$fieldLocal) = pluginSplit($field);

		$pluralVar = Inflector::pluralize($fieldLocal);

		$values = !empty($opts['options']) ? $opts['options'] : $this->getVar($pluralVar);
		$path = !empty($opts['path']) ? $opts['path'] : "/images";

		?>
		<div id='<?= $domId ?>_thumbs' class='row thumbs'>
		<?  foreach($values as $value=>$name) { ?>
			 <?= $this->Html->link($this->Html->image("$path/$value.png?rand=".rand(10000,99999))."<br/>$name", "#", array('data-value'=>$value,'class'=>"thumb ".($selected==$value?" active":""),'title'=>$name)) ?>
		<? } ?>
		</div>

		<script>
		$('#<?= $domId ?>_thumbs a').click(function(e) {
			e.preventDefault();
			$('#<?= $domId ?>_thumbs a').removeClass('active');
			$(this).addClass('active');
			$('#<?= $domId ?>').val($(this).data('value')).change();
		});
		</script>
		<style>
			.thumbs
			{
				text-align: center;
			}
			.thumbs a.thumb
			{
				display: block;
				float: left;
			}
			.thumbs a.thumb img
			{
				margin: 2px;
				border: solid #AAA 5px;
				border-radius: 5px;
				width: 150px;
				height: 150px;
			}
			.thumbs a.thumb.active img
			{
				border-color: red;
			}
			.thumbs a.thumb:hover,
			.thumbs a.thumb:active,
			.thumbs a.thumb:visited
			{
				text-decoration: none;
			}

		</style>
		<?
		return ob_get_clean();
	}

	function expiration($fieldMonth,$fieldYear, $fieldMonthOpts = array(), $fieldYearOpts=array())
	{
		$fieldYearOpts['empty'] = false;
		$fieldMonthOpts['empty'] = false;
		$fieldMonthOpts['default'] = date('m');

		ob_start();
		?>
		<div class='input date'>
	        <label>Expiration</label>
	        <div>
	                <?= $this->select($fieldMonth, $this->months, $fieldMonthOpts); ?>
	                <? $years = range(date('Y'), date('Y')+10); ?>
	                <?= $this->select($fieldYear, array_combine($years,$years), $fieldYearOpts); ?>
	        </div>
	        </div>
		<?
		return ob_get_clean();
	}



}
