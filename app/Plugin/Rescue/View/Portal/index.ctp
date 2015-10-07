<? $this->assign("page_title", "Hopeful Press: Find your next pet, list adoptables, collaborate with rescuers, fosters, volunteers and transporters"); ?>

<div class='index'>
	
	<div class='tab-content'>
		<div id='adopt' class='tab-pane active'>
			<?= $this->Form->create("Adoptable"); ?>
			<div class='row'>
				<?= $this->Form->input("location",array('div'=>'col-md-3','placeholder'=>'Philadelphia, PA')); ?>
				<?= $this->Form->input("species",array('div'=>'col-md-3','options'=>$species)); ?>
				<?= $this->Form->input("breed",array('div'=>'col-md-3','type'=>'select','options'=>array())); ?>
				<?= $this->Form->save("Search",array('div'=>'col-md-3','cancel'=>false)); ?>
			</div>
			<?= $this->Form->end();  ?>

			<div id='adoptables'>
				<?= $this->requestAction("/rescue/adoptables/widget",array('return')); ?>
			</div>
		</div>
		<div id='rescue' class='tab-pane '>
			<?= $this->Html->add("List adoptable", array('controller'=>'adoptables','action'=>'add')); ?>

			<h3>Local Rescues</h3>
			<div id='rescues'>
				<?= $this->requestAction("/rescues/widget",array('return')); ?>
			</div>
		</div>
		<div id='foster' class='tab-pane '>
			<?= $this->Html->add("List foster services", array('controller'=>'fosters','action'=>'add')); ?>
			<?= $this->Html->search("Find foster services", array('controller'=>'fosters','action'=>'search')); ?>
			<?= $this->Html->add("Post request for foster services", array('controller'=>'fosters','action'=>'iso')); ?>
		</div>
		<div id='transport' class='tab-pane '>
			<?= $this->Html->add("List transporter services", array('controller'=>'transporters','action'=>'add')); ?>
			<?= $this->Html->search("Find transporter services", array('controller'=>'transporters','action'=>'search')); ?>
			<?= $this->Html->add("Post request for transporter services", array('controller'=>'transporters','action'=>'iso')); ?>
		</div>
		<div id='volunteer' class='tab-pane '>
		</div>
	</div>
</div>
