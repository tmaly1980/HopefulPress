<?
App::uses("RescueAppModel", "Rescue.Model");
class Adoptable extends AppModel
{
	var $actsAs  = array(
		'Core.Dateable'=>array(
			'fields'=>array('birthdate','date_fosterable','date_available','date_adopted'), # Not birthdate since we format that relatively...
		),
	);

	# dropdown values
	var $genders = array('Male','Female');
	var $adult_sizes = array('Small','Medium','Large');
	var $age_groups = array('Youth','Young Adult','Adult','Senior'); #  Lifespan varies so much it's easier to just ask
	var $statuses = array('Available','Retreived','Pending Adoption','Adopted');
	var $sanctuary_statuses = array('Featured','Private');
	var $energy_levels = array('','Low','Medium','High');

	var $virtualFields = array(
		'select_label' => "CONCAT(Adoptable.name, ', ', Adoptable.gender, ', ', Adoptable.breed, IFNULL(DATE_FORMAT(Adoptable.birthdate, ', born %m/%d/%Y'),', unknown age'))"
	);

	###########
	var $hasOne = array(
		'Owner'=>array('className'=>'Adoption','foreignKey'=>'adoptable_id','conditions'=>array('Owner.status'=>array('Accepted','Pending'))),
		# only fill in 'Owner' if adoption application had been accepted
		# but give option to view details and populate (ie set as accepted)
		# LIST APPLICANTS... ajax populate...
		# view popup....
		# WHAT ABOUT PENDING????
		# also allow 'clear/reset'
	);

	var $hasMany = array(
		'ProspectiveOwner'=>array('className'=>'Adoption','foreignKey'=>'adoptable_id'),
		'Photos'=>array('className'=>'AdoptablePhoto','order'=>'Photos.ix IS NULL, Photos.ix ASC,Photos.id ASC'),
		'AdoptionStory'=>array('className'=>'AdoptionStory'),
		#'Video'=>array('className'=>'Rescue.AdoptableVideo','foreignKey'=>'adoptable_id'),
	);

	var $belongsTo = array(
		'AdoptablePhoto'=>array('className'=>'AdoptablePhoto'),
		'Rescue'=>array('className'=>'Rescue'),
	);
	
	var $import_fields = array('name','species','breed','birthdate','gender','status','date_adopted','microchip','Owner.first_name','Owner.last_name','Owner.address','Owner.address_2','Owner.city','Owner.state','Owner.zip_code','Owner.home_phone','Owner.cell_phone','Owner.work_phone','Owner.email');


	function adopted_list()
	{

		$this->virtualFields['adopted_label'] = "CONCAT(Adoptable.name, ', adopted ', IFNULL(DATE_FORMAT(Adoptable.date_adopted, '%m/%d/%Y'),'Unknown'), ', ', COALESCE(Owner.first_name,''), ' ', COALESCE(Owner.last_name,''))";
		$results = $this->find('all',array('fields'=>array('id','adopted_label'),'conditions'=>array('Adoptable.status'=>'Adopted')));

		$adopted_list = Set::combine($results, "{n}.Adoptable.id", "{n}.Adoptable.adopted_label");

		return $adopted_list;
	}

	function  available_list()
	{
		return $this->find('list',array('fields'=>array('id','select_label'),'conditions'=>array('Adoptable.status'=>'Available')));
	}
}
