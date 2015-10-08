<?
if(empty($model)) { $model = 'Adoptable'; }

# Load from session here? To not trigger form saving elsewhere...
if(empty($this->request->data["{$model}Search"]))
{
	$this->request->data["{$model}Search"] = $this->Session->read("Search");
}
$distances = array(
	10=>'10 mi',
	25=>'25 mi',
	50=>'50 mi',
	100=>'100 mi',
	250=>'250 mi',
	500=>'500 mi'
);

if(empty($sortby))
{
	$sortby = array(
		'distance'=>'Closest',
	);
}
$where = $this->Session->read("Geo.where"); 
$defaultLocation = !empty($where) ? join(", ", array($where['city'], $where['region_code'])) : null; # IP Guess... or previous capture of GPS
?>
<div>
	<? if(!empty($controls)) { ?>
	<div class='right padding5'>
		<?= $controls?>
	</div>
	<? } ?>
	<? if(!empty($heading)) { ?>
	<h3 class='margin0 padding10'><?= $heading ?></h3>
	<? } ?>
	<div class='clear'></div>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#search_bar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="search_bar">
	<?= $this->Form->create("{$model}Search",array('url'=>array('controller'=>Inflector::pluralize(Inflector::underscore($model)),'action'=>'search'),'class'=>'navbar-form navbar-left form-inline','role'=>'search')); ?>
		<?= $this->Form->input("location",array('id'=>'SearchLocation','div'=>'Xcol-md-2','label'=>false,'placeholder'=>'Location (city, state)','default'=>$defaultLocation)); ?>
		<?= $this->Form->input("distance",array('Xdiv'=>'col-md-2','label'=>false,'options'=>$distances,'default'=>25)); ?>
		<?= $this->Form->input("species",array('Xdiv'=>'col-md-2','label'=>false,'id'=>'SearchSpecies','options'=>$species)); ?>
		<?= $this->Form->input("breed",array('Xdiv'=>'col-md-2','label'=>false,'id'=>'SearchBreed','style'=>empty($this->Form->data['AdoptableSearch']['breed'])?"display:none;":"",'type'=>'select','options'=>array())); ?>
		<?= $this->Form->input("sort",array('Xdiv'=>'inline-label','type'=>'select','options'=>$sortby)); ?>
        	<button type="submit" class="btn btn-success">Search</button>
		<? if(!empty($browse)) { ?>
			<?= $browse ?>
		<? } ?>
	<?= $this->Form->end();  ?>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	<script>
	// Load breeds for species...
	var breeds = <?= json_encode($breeds); ?>;
	$('#SearchSpecies').change(function() {
		var species = $(this).val();
		var breedsForSpecies = species ? breeds[species] : null;
		$('#SearchBreed').setoptions(breedsForSpecies,null,null,'Any breed');
		if(!breedsForSpecies || breedsForSpecies.length <= 1) // Just empty.
		{
			$('#SearchBreed').hide();
		} else {
			$('#SearchBreed').show();
		}
	});
	$(document).ready(function() {
		$('#SearchSpecies').change();
	});

	// Try to get their GPS location, so we can be more accurate (IP address is dead wrong on phones)
	$(document).ready(function() {
	/*
		// How do we overwrite location if just IP guess, but not if specific?
		if(navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(function(pos) {
				console.log(pos);
				var params = pos.coords;
				$.post('/geo/locator/locate', params, function(response) {
					if(response.city_state)
					{
						// REMOVE IP GUESS if we have something better 
						<? if(!$this->Session->read("Search.Adoptable.location")) { # Did not yet search. ?>
							$('#SearchLocation').val(''); 
						<? } ?>
						if(!$('#SearchLocation').val()) { $('#SearchLocation').val(response.city_state); }
					}
				},'json');
			});
		}
	*/
	});
	</script>
</div>


