<?
/*
LOGIC:

Click on anchor tells chart it's config

Chart "owns" params and loads appropriate form (years/etc, units)

Form submit updates graph data (forces graph to reload data while graph has kept it's config)



*/
class ChartHelper extends AppHelper
{
	var $helpers = array('Form','Html');

	/*
	XXX load form dates once in PHP, since doesnt change 


	*/

	function form($params = array())
	{
		$dateslist = !empty($this->_View->viewVars['dateslist']) ?
			$this->_View->viewVars['dateslist'] : array();
		# If named params are passed, treat them as defaults!!
		if(!empty($this->request->named['year']))
		{
			$year = $this->request->named['year'];
			$this->request->data['datespan'] = 'year';
			$this->request->data['year'] = $year;
		} else if(!empty($this->request->named['quarter'])) {
			$quarter = $this->request->named['quarter'];
			$this->request->data['datespan'] = 'quarter';
			$this->request->data['quarter'] = $quarter;
		} else if(!empty($this->request->named['month'])) {
			$month = $this->request->named['month'];
			$this->request->data['datespan'] = 'month';
			$this->request->data['month'] = $month;
		} else if(!empty($this->request->named['week'])) {
			$week = $this->request->named['week'];
			$this->request->data['datespan'] = 'week';
			$this->request->data['week'] = $week;
		} 

		if(empty($this->request->data['year']))
		{
			$this->request->data['year'] = date('Y');
		} else if(empty($this->request->data['quarter'])) {
			$this->request->data['quarter'] = sprintf("%s Q%d",
				date('Y'), ceil(date('m')/3));
		} else if(empty($this->request->data['month'])) {
			$this->request->data['month'] = date('m');
		} else if(empty($this->request->data['week'])) {
			$this->request->data['week'] = date('Y \WW');
		}

		$id = !empty($params['id']) ? $params['id'] : 'chartForm';
		$graph = !empty($params['graph']) ? $params['graph'] : 'graph';
		ob_start();
		?>
		<div class='visible-xs-block visible-sm-block right'>
			<button id='<?= $id ?>_toggler' class='btn btn-primary'><i class='fa fa-gear fa-lg'></i></button>
		</div>
		<?= $this->Form->create(false, array('id'=>$id,'class'=>'form-inline dateForm hidden-sm hidden-xs')); ?>
			<?= $this->Form->input('unit',array('id'=>'units','label'=>'Units: ','options'=>array())); ?>

			<div class='form-group datefields'>
				<?= $this->Form->input('datespan',array('id'=>'datespan','label'=>'Display: ',
					'options'=>array('year'=>'Year','quarter'=>'Quarter','month'=>'Month','week'=>'Week'),
					'default'=>'year'
				)); # yearly, quarterly, monthly, weekly ?>
	
				<?= $this->Form->input('year',array('class'=>'spantype','id'=>'year','label'=>false,'options'=>$dateslist['year'])); # What's available for selected datespan ?>
				<?= $this->Form->input('quarter',array('class'=>'spantype','id'=>'quarter','label'=>false,'options'=>$dateslist['quarter'])); # What's available for selected datespan ?>
				<?= $this->Form->input('month',array('class'=>'spantype','id'=>'month','label'=>false,'options'=>$dateslist['month'])); # What's available for selected datespan ?>
				<?= $this->Form->input('week',array('class'=>'spantype','id'=>'week','label'=>false,'options'=>$dateslist['week'])); # What's available for selected datespan ?>

				<?#= $this->Form->input('Update', array('label'=>false,'type'=>'button','class'=>'btn btn-primary')); ?>
			</div>


			<div>
			<!--
			<?= $this->Html->link("Compare", "#", array('id'=>'compare')); ?>
			load another dropdown? load checkbox list?
			-->
			</div>
		<?= $this->Form->end(); ?>
		<script>
		$('#<?=$id?>_toggler').click(function()
		{
			if($('#<?=$id?>').hasClass('hidden-xs'))
			{
				$('#<?= $id ?>').removeClass('hidden-xs hidden-sm');
			} else {
				$('#<?= $id ?>').addClass('hidden-xs hidden-sm');
			}
		});
		$('#datespan').change(function() {
			$('#<?=$id?>').trigger('refresh');
			$('#<?=$id?>').submit();
		});
		$('#<?=$id?>').bind('refresh', function() { // load date fields
			var span = $('#datespan').val();
			$('#<?=$id?> .spantype').hide();
			$('#'+span).show();
		});
		$('#units').change(function() { $('#<?=$id?>').submit(); }); // instant

		$('#<?=$id?> .spantype').change(function() { 
			// Load proper other select.

			//$('#<?=$id?>').delay(5000).queue(function() { // delay since might be choosing other options.
				$('#<?=$id?>').submit();
			//});
		});
		$('#<?=$id?>').submit(function(e) {
			e.preventDefault();
			$('#<?= $id ?>').addClass('hidden-xs hidden-sm'); // Hide options

			$('#<?= $graph ?>').loadGraph(null); // Update, includes change in title
			// Update sub-title

			$('#<?=$id?>').clearQueue(); // Clear any submits waiting.
		});
		$(document).ready(function() {
			$('#<?= $id ?>').trigger('refresh'); // 
		});
		</script>

		<?
		return ob_get_clean();
	}

	function container($id = 'graph')
	{
		ob_start();
		?>
        	<div id='<?=$id?>' class='graph_container'>
                	<div class='caption'><span class='title'></span><span class='subtitle'></span></div>
                	<div class='graph container-fluid'></div>
        	</div>
		<?
		return ob_get_clean();
	}


}
