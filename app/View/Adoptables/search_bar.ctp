<?
$heading = "Find your next furry companion: $adoptableCount adoptables available now";
$controls = (empty($this->Html->user("Rescue.id")) ? 
	"<b>Manage a rescue?</b> ":"").
	$this->Html->add("Add an FREE adoptable listing","/rescuer/adoptables/add",array('class'=>'btn-primary'));
	#.$this->Html->add("Create a FREE rescue account","/rescuer/rescues/add",array('class'=>'btn-primary'))):

$sortby = array(
	'distance'=>'Closest',
	'created_asc'=>'Listed the longest', # Ie old postings, animals hard to adopt....
	'created_desc'=>'Recently added',
	# OTHER STUFF???
);
echo $this->element("portal/search_bar",array('model'=>'Adoptable','sortby'=>$sortby,'heading'=>$heading,'controls'=>$controls));

