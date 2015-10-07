<?
class SingletonBehavior extends ModelBehavior
{ # keeps at most one record of a table, per site. and if all critical content deleted, the page is hidden (deleted) from public

	# To get proper existing id(), we have to call $model->id = $model->first_id()..
	# Unfortunately, beforeSave() here is too late, as exists() is called before it, and ->id is not set, so it fails and INSERTS instead of UPDATES

	# beforeSave() is useless (for ensuring singletons), since it's too late (code already determined whether INSERT or UPDATE).

	function afterSave(Model $model, $created, $options = array())
	{
		if(!Configure::read("site_id")) { return true; } # Skip unless multisite enabled.

		// If record has no critical fields, then delete the record. act like page is blank so we reset and hide from public.
		if(!empty($model->minimal_fields))
		{
			# We cannot count on save to include all fields, so we need to re-read from db.

			$record = $model->read(); # Gather from ->id set on save()
			$record[$model->alias] = array_filter($record[$model->alias]); # Skip over blank values.

			error_log("ODL REC=".print_r($record,true));

			$valid_fields = false;

			if($model->minimal_fields === true) # Just check for varchar/text fields
			{
				error_log("MINIMAL ALL");
				foreach($record[$model->alias] as $k=>$v)
				{
					if(in_array($model->_schema[$k]['type'], array('string','text')))
					{
						$valid_fields = true; 
						break;
					}
				}

			} else { # otherwise explicit.
				$valid_fields = array_intersect_key($record[$model->alias], array_flip($model->minimal_fields)); # Has at least one.
			}

			error_log("VALID=".print_r($valid_fields,true));

			if(empty($valid_fields))
			{
				error_log("{$model->alias} #{$model->id} HAS NO IMPORTANT DATA, DELETING/HIDING FROM PUBLIC=".print_r($record[$model->alias],true));
				$model->delete();
			}
		}

		# Cleanup other records so only one, ie if 2+ by accident.
		if(!empty($model->id))
		{
			#error_log("DELETING EXTRANEOUS RECORDS (< {$model->id} since we seem to have just added one by accident!");
			$model->deleteAll(array("{$model->alias}.{$model->primaryKey} != {$model->id}"));
		}
	}

	function singleton(Model $model) # AUTO create record if not there...
	{
		#error_log("CALLING SINGLETON ON {$model->alias}");
		$model->id = false;
		$record = $model->first();
		if(empty($record) && Configure::read("site_id"))
		{
			$model->create();
			$model->save();
			$record = $model->read();
		}
		return $record;
	}

	/* make it possible to NOT necessarily create record automatically , via find() vs  singleton()
	function beforeFind(Model $model, $query) # Auto-create record if needed!
	{
		if(!Configure::read("site_id")) { return true; } # Skip unless multisite enabled.
		if($model->findQueryType == 'first')
		{
			$query['recursive'] = -1;
			$query['callbacks'] = false;
			if(!$model->find('count', $query))
			{
				$model->create();
				$model->save();
			}
		}
		return true;
	}
	*/
}
