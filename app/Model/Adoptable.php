<?
App::uses("RescueAppModel", "Rescue.Model");
class Adoptable extends AppModel
{
	var $actsAs  = array(
		'Core.Dateable'=>array(
			'fields'=>array('story_date','birthdate','date_fosterable','date_available','date_adopted'), # Not birthdate since we format that relatively...
		),
	);

	# dropdown values
	var $genders = array('Male','Female');
	var $adult_sizes = array('Small','Medium','Large');
	var $age_groups = array('Baby','Youth','Young Adult','Adult','Senior'); #  Lifespan varies so much it's easier to just ask
	var $statuses = array('Not Available Yet','Retreived','Pending Adoption','Adopted','Lost','Deceased');
	var $sanctuary_statuses = array('Featured','Private');
	var $energy_levels = array('','Low','Medium','High');

	var $virtualFields = array(
		'select_label' => "CONCAT(Adoptable.name, ', ', Adoptable.gender, ', ', Adoptable.breed, IFNULL(DATE_FORMAT(Adoptable.birthdate, ', born %m/%d/%Y'),', unknown age'))"
	);

	###########
	var $hasOne = array(
	);

	var $hasMany = array(
		#'ProspectiveOwner'=>array('className'=>'Adopter','foreignKey'=>'adoptable_id'),
		'Photos'=>array('className'=>'AdoptablePhoto','order'=>'Photos.ix IS NULL, Photos.ix ASC,Photos.id ASC'),
		#'AdoptionStory'=>array('className'=>'AdoptionStory'),
		#'Video'=>array('className'=>'Rescue.AdoptableVideo','foreignKey'=>'adoptable_id'),
	);

	var $belongsTo = array(
		'Owner'=>array('className'=>'Adopter','foreignKey'=>'adopter_id','conditions'=>array('Owner.status'=>'Approved')),
		'AdoptablePhoto'=>array('className'=>'AdoptablePhoto'),
		'Rescue'=>array('className'=>'Rescue'),
		'SuccessStoryPhoto'=>array('className'=>'SuccessStoryPhoto','foreignKey'=>'success_story_photo_id'),
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
