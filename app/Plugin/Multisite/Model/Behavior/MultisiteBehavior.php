<?
# Implements site assignment/restriction to reading/writing, based on site_id config var.
class MultisiteBehavior extends ModelBehavior
{
	# Only happens if 'site_id' field exists, and model doesn't have 'autosite'=>false

	function beforeFind(Model $model, $query = array()) # Restrict to current site.
	{
		$site_id = Configure::read("site_id");
		#$field = ($model->alias == 'Site') ? 'id' : 'site_id'; # For Site, filters queries purely to own site.
		$field = 'site_id';

		if(!$model->hasField($field) || empty($site_id) || (isset($model->autosite) && $model->autosite === false) || (isset($query['autosite']) && $query['autosite'] === false)) { 
			return $query; 
		}
		# Can disable by setting $model->autosite = false (even when site_id there)

		if($model->alias == 'Site') { $model->id = $site_id; }

		#if($model->autosite === null) { # Record not in any site. # Makes no sense....
		#	$site_id = null;
		#}
		# Only append site_id if not already there.

		if(!is_array($query['conditions'])) { $query['conditions'] = array($query['conditions']); } # Fix so check below works.

		if(isset($query['conditions'][$field]) || isset($query['conditions'][$model->alias.".$field"])) { 
			# Ignore (allow any) if site_id is set to false
			if(isset($query['conditions'][$field]) && $query['conditions'][$field] === false)
			{
				unset($query['conditions'][$field]);
			}
			if(isset($query['conditions'][$model->alias.".$field"])) 
			{
				unset($query['conditions'][$model->alias.".$field"]);
			}
			return $query;
		}

		if(!empty($model->bypassSiteField)) # Intersite potential...(mostly for manager users)
		{
			$query['conditions']['OR'] = array(
				$model->alias.".site_id" => $site_id,
				$model->alias.".".$model->bypassSiteField => 1 # ie manager=>1
			);
		} else {
			$query['conditions'][$model->alias.".site_id"] = $site_id;
		}

		return $query;
	}

	function beforeSave(Model $model, $options = array())
	{ # Problem with filtering out manager user accounts is that manager flag probably won't be passed

		$site_id = Configure::read("site_id");
		#$field = ($model->alias == 'Site') ? 'id' : 'site_id'; # For Site, filters queries purely to own site.
		$field = 'site_id';

		#error_log("BEFORE SAVE, SITEID=$site_id, D=".print_r($model->data,true));
		if(!$model->hasField($field) || empty($site_id) || (isset($model->autosite) && $model->autosite === false) || (isset($query['autosite']) && $query['autosite'] === false)) { 
			return true;
		}
		#

	#	if($model->alias == 'Site') { $model->id = $site_id; error_log("SET SITE->id = $site_id"); }
		# TOO LATE, after logic 

		if(isset($model->data[$model->alias][$field])) { return true; }

		$model->data[$model->alias][$field] = $site_id;

		#error_log("SETTING {$model->alias} $field =>> $site_id");

		if(!empty($model->whitelist)) # Otherwise no restrictions.
		{
			$model->whitelist[] = $field; # Add site_id so works with saveField()
		}
		return true;
	}

}
