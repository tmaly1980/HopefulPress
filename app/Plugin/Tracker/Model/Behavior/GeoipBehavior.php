<?

class GeoipBehavior extends ModelBehavior
{
	function beforeSave(Model $model, $options = array())
	{
		if(!$model->hasField("ip") && !$model->hasField("ip_address")) { return true; } # SKIP
		# Take ip/ip_address and populate fields....
		# city, state, country, zipCode

		$ip = null;

		if(!empty($model->data[$model->alias]['ip']))
		{
			$ip = $model->data[$model->alias]['ip'];
		}
		else if(!empty($model->data[$model->alias]['ip_address']))
		{
			$ip = $model->data[$model->alias]['ip_address'];
		}


		if(!empty($ip))
		{
			error_log("IP=$ip, MOD={$model->alias}, D=".print_r($model->data[$model->alias],true));
			$result = $this->lookupIp($ip);
			error_log("IP LOOKUP RESULT=".print_r($result,true));

			foreach($result as $k=>$v)
			{
				if($model->hasField($k))
				{
					$model->data[$model->alias][$k] = $v;
				}
			}

			if(!empty($result['country_name']) && $model->hasField("country"))
			{
				$model->data[$model->alias]['country'] = $result['country_name'];
			}
			if(!empty($result['region']) && $model->hasField("state"))
			{
				$model->data[$model->alias]['state'] = $result['region'];
			}
			if(!empty($result['postal_code']) && $model->hasField("zipCode"))
			{
				$model->data[$model->alias]['zipCode'] = $result['postal_code'];
			}
			
		}

		return true;
	}

	function lookupIp($ip) { # Slimmest, than some datasource, etc.
	    $rc = App::import("Vendor", "Tracker.geoip");
	    $rc = App::import("Vendor", "Tracker.geoipcity");
	    #App::uses("geoipcity", "Vendor");
	
	    $gi = geoip_open(APP."/Plugin/Tracker/Vendor/GeoLiteCity.dat", GEOIP_STANDARD);
	    $result = geoip_record_by_addr($gi, $ip);
	    geoip_close($gi);
		if(empty($result)) { return array(); }
	    
	    return get_object_vars($result);
	}
}
