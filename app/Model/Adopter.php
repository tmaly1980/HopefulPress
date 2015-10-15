<?
App::uses("RescueAppModel", "Rescue.Model");
class Adopter extends AppModel
{
	var $order = "%ALIAS%.status ASC";

	# DROPDOWNS
	var $statuses = array('Received','Pending','Approved','Deferred','Denied');
	###############

	var $belongsTo = array(
		'User',
		'Adoptable'=>array('className'=>'Adoptable','foreignKey'=>'adoptable_id')
	);

 	public $virtualFields = array(
		'full_name'=>"CONCAT(%ALIAS%.first_name, ' ', %ALIAS%.last_name)",
	);

	var $json_fields = array('data','pet_ownership_history','home_details','care_and_responsibility','preference','references','custom');
	# XXX If  used in future, make automagic given class var....

	function afterFind($results,$primary=false)
	{
		return $this->jsonDecode($results,$this->json_fields);
	}

	function beforeSave($Options=array())
	{
		$this->jsonEncode($this->json_fields);
		return true;
	}

}
