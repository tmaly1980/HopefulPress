<? $this->assign("page_title", "Animal Rescuers"); ?>
<div class='index'>
	<div class='row'>
		<div class='col-md-6 '>
			<div class='border rounded padding25 margin25'>
			<h3>Post adoptables</h3>
			<p>
				If you're a local animal rescue, you can add your listing to our directory or post your adoptable listings on this website
			</p>
			<?= $this->Html->add("Post an adoptable listing",array('rescuer'=>1,'controller'=>'adoptables','action'=>'add')); ?>
			<?= $this->Html->add("Add rescue to directory",array('rescuer'=>1,'controller'=>'rescues','action'=>'add')); ?>
			</div>
		</div>
		<div class='col-md-6 '>
			<div class='border rounded padding25 margin25'>
			<h3>Find a local rescue</h3>
			<p>
				If you have found a stray animal, need to surrender an animal, or if you're trying to find a local rescue
			</p>
			<?= $this->Html->search("Find a rescue",array('action'=>'search'),array('class'=>'btn-primary')); ?>
			</div>
		</div>
	</div>
</div>
