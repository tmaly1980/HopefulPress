<?
$heading = "Find your next furry companion: $adoptableCount adoptables available now";

$sortby = array(
	'distance'=>'Closest',
	'created_asc'=>'Listed the longest', # Ie old postings, animals hard to adopt....
	'created_desc'=>'Recently added',
	# OTHER STUFF???
);
echo $this->element("portal/search_bar",array('model'=>'Adoptable','sortby'=>$sortby,'heading'=>$heading));

