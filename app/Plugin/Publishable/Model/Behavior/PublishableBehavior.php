<?

class PublishableBehavior extends ModelBehavior
{
	# Add 'Draft' hasOne
	function setup(Model $model, $settings = array())
	{
		if($model->hasField("draft_id"))
		{
			$model->bindModel(array('hasOne'=>array(
				#"Draft{$model->name}"=>array(
				"Draft"=>array(
					'className'=>$model->name,
					'foreignKey'=>"draft_id"
				)
			)));
		}

		return parent::setup($model,$settings);
	}

	# Ready to preview before publishing.
	function draft($model, $data)
	{
		# See if existing ID, and if so, move that to draft_id if possible.
		# Otherwise, we're going to accidentally be hiding a page if its been modified.
		error_log("DRAFTING? =".print_r($data,true));
		if($model->hasField("draft_id") && empty($data[$model->alias]['draft_id']) && !empty($data[$model->alias]['id']))
		{
			# Create duplicate record with new id. Form may not have all fields.
			$draft_id = $data[$model->alias]['id'];
			error_log("DRAFT ORIGINAL=$draft_id");
			$copy = $model->read(null, $draft_id);
			$copy[$model->alias]['draft_id'] = $draft_id; # Move over so we don't overwrite.

			unset($copy[$model->alias][$model->primaryKey]);

			# Clear in form data the things we are overwriting.
			unset($data[$model->alias][$model->primaryKey]); # Don't accidentally write back later.
			unset($data[$model->alias]['draft_id']); # Don't accidentally write back later.

			error_log("COPY (OF $draft_id)=".print_r($copy,true));

			# May need to overwrite EXISTING draft, so search for id
			if($existing_id = $model->field($model->primaryKey, array('draft_id'=>$draft_id)))
			{
				$copy[$model->alias][$model->primaryKey] = $model->id = $existing_id;
			} else {
				$model->create();
			}
			#$model->save($copy);
			$data = $copy;
		} else {
			error_log("SHOOT, NOT DRAFT OR EMPTY ID");

		}

		$data[$model->alias]['published'] = null;

		return $model->save($data);
	}

	# All ready to go and share publically.
	function publish($model, $id)
	{
		$model->id = $id;
		$draft_id = $model->hasField("draft_id") ? $model->field("draft_id") : null;

		error_log("PUBLISH $id, root DRAFT ID=$draft_id");

		if(!empty($draft_id) && $id != $draft_id) # Has a master we need to erase.
		{
			$model->delete($draft_id);

			# Need to update the record indirectly, since changing the id will change the reference record.
			$model->updateAll(array("{$model->alias}.{$model->primaryKey}"=>$draft_id, "{$model->alias}.draft_id"=>null), array("{$model->alias}.{$model->primaryKey}"=>$id));
			# XXX need to make sure we dont do an accidental UPDATE
			$model->set($model->primaryKey, $draft_id);
			error_log("PUB MOVING $id => $draft_id");
		}

		$now = date("Y-m-d H:i:s");
		$model->set("published", $now);


		if($model->save())
		{
			# Save update as well.
			if(!empty($model->belongsTo['Update']) && $model->hasField("update_id") && ($update_id = $model->field("update_id"))) 
			{
				error_log("SAVING UPDATE... ID=$update_id, PUB=$now");
				# Publish the update as well.
				$model->Update->id = $update_id;
				$model->Update->saveField("published", $now);

				# Remove any other updates (ie drafted)
				# BUT ONLY IF WE ARE A DRAFT OF SOMETHING ELSE (not original)
				if(!empty($draft_id))
				{
					$model->Update->deleteAll(array('model'=>$model->alias, 'model_id'=>$id),false);
					error_log("DELETING UPDATE ASSIGNED TO draft $id SINCE ORIGINAL=$draft_id");
				}
			}

			error_log("PUBLISHED=$draft_id OR $id");


			return !empty($draft_id) ? $draft_id : $id; # So we can redirect properly
		}
		return false;
	}

	# Need to hide from public.
	function unpublish($model, $id)
	{
		$model->id = $id;
		if($model->saveField("published", null))
		{
			if(!empty($model->belongsTo['Update']) && $model->hasField("update_id") && ($update_id = $model->field("update_id")))
			{
				# Unpublish the update as well.
				$model->Update->id = $update_id;
				$model->Update->saveField("published", null);
			}
			
			return true;
		}
		return false;
	}

	function findIndex($model, $query = array()) # Suitable for index(), where drafts of other pages are excluded.
	{
		if ($model->hasField("draft_id")) {
			$query['conditions']["{$model->alias}.draft_id"] = null;
		}
		return $model->find('all', $query);
	}

	function findPublished($model, $query = array())
	{
		if ($model->hasField("published")) {
			$query['conditions'][] = "{$model->alias}.published";
		}
		return $model->find('all', $query);
	}
	function findUnpublished($model, $state, $query, $results = array()) 
	{
		if ($model->hasField("published")) {
			$query['conditions'][] = "{$model->alias}.published IS NULL";
		}
		return $model->find('all', $query);
	}

	function findPublishedCount($model, $query = array()) 
	{
		if ($model->hasField("published")) {
			$query['conditions'][] = "{$model->alias}.published";
		} else { return null; }
		return $model->find('count', $query);
	}
	function findUnpublishedCount($model, $query = array()) 
	{
		if ($model->hasField("published")) {
			$query['conditions'][] = "{$model->alias}.published IS NULL";
		} else { return null; } # Irrelevent.
		return $model->find('count', $query);
	}

	# Hide unpublished if not signed in
	function beforeFind(Model $model, $query)
	{ # Must return query to continue, else will halt.
		if(!$model->hasField("published")) { return $query; }

		# LET SPECIAL CASES GET ACCESS TO ALL ITEMS...
		# special counts will use this approach.
		if(isset($query['conditions']["{$model->alias}.published"]) || isset($query['conditions']["published"]))
		{
			return $query; # Leave alone, explicit.
		}

		# Let admin index, count, view see all. (but not widgets)
		# YES WIDGETS!
		if(Configure::read("in_admin"))# && in_array($model->findQueryType, array('all','count','first'))) 
			{ return $query; }

		#########################################
		# OTHERS GET FILTERED.... hide unpublished.
		#
		# Widget never shows since it uses 'recent'...
		# Public never sees unpublished.

		if($model->hasField("published"))
		{
			$query['conditions'][] = "{$model->alias}.published";
		}

		return $query;
	}


}
