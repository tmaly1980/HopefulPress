<?
class ZipCode extends AppModel
{
	# So far, this only works for US....
	# 
	function latlong($latitude,$longitude)
	{
		$distance = 2;

		$result = $this->find('first',array(
			'fields'=>array('*', "(((acos(sin(($latitude*pi()/180)) * sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($longitude - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS distance"),
			'having'=>"distance < $distance",
			'order'=>'distance ASC',
			'limit'=>1));
		return $result;

	}
}
