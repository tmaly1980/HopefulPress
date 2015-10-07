<?
App::uses("Geolocate", "Geo.Lib"); 
class LocatorController extends AppController
{
	var $uses = array('Geo.Location');

	function locate()
	{
		error_log("GOT=".print_r($this->request,true));
		if(!empty($this->request->data)) { 
			$lat = $this->request->data['latitude'];
			$long = $this->request->data['longitude'];

			# Accuracy of lat/long is 

			# This is just a guess, so we probably round a bit and then sort to get the closest one, within limits (ie 
			# 
			$where = $this->ZipCode->latlong($lat,$long);
			error_log("WHERE=".print_r($where,true));

			if(!empty($where))
			{
				$this->Json->set("city_state", join(", ", $where['ZipCode']['city'], $where['ZipCode']['state']));
				$this->Session->write("Geo.where", $where); # Possibly re-use elsewhere.
			}
			return $this->Json->render();
		}
		return $this->Json->error("latitude/longitude not provided");
	}

}
