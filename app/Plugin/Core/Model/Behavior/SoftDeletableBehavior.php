<?php
/**
 * @todo Support HasAndBelongsToMany relationships.
 * @todo Support more column types than boolean and datetime.
 * @todo Implement restoreAll() for exclusive associations.
 * @todo Add model association option to not filter deleted records.
 */
class SoftDeletableBehavior extends ModelBehavior {
	var $field = 'deleted';
	var $settings = array();
/**
 * Setup method for behavior instatiation - merge model settings.
 * 
 * @param object $model Model instance
 * @param array $settings Settings passed in from model
 */	
	function setup(Model $model, $config = array()) {
		$this->enableDeletable($model);
		
		# Set associated model conditions
		foreach (array('hasOne', 'hasMany', 'belongsTo') as $type) {
			foreach ($model->$type as $alias => &$params) {
				if (!isset($model->$alias)) {
					continue;
				}
				
				$association =& $model->$alias;
				if ($this->_isDeletable($association)) {
					$params['conditions'][$association->alias . '.' . $this->field] = $this->_notDeleted($association);
				}
			}
		}
	}
/**
 * When a record is deleted, set 'deleted' field to true and cancel hard delete. If a
 *  record has already been soft deleted, continue with the hard delete.
 * 
 * @param object $model Model instance
 * @param boolean $cascade True if model cascades deletes
 * @return boolean True if 'deleted' field doesn't exist (continue with hard delete)
 */	
 	function beforeDelete(Model $model, $cascade = true)
	{
		$model->softDeleted = false;
		return true;
	}

 	/* This is just fucking confusing...

	function beforeDelete(&$model, $cascade) {
		error_log("SOFT_DELETE"); # FUCKER
		if ($model->hasField($this->field)) {
			$_deleted = $model->find('count', array(
				'isDeleted' => true,
				'conditions' => array($model->escapeField($model->primaryKey) => $model->id)
			));

			$model->softDeleted = false;
			
			if (!$this->settings[$model->name]['enabled'] || $_deleted) {
				return true;
			}
			
			if ($this->_update($model, $this->_deleted($model)) && $cascade) {
				$model->_deleteDependent($model->id, $cascade);
			}
			$model->softDeleted = true;
			$model->afterDelete();		
			return false;
		}		
		return true;
	}
	*/
/**
 * Filter out deleted records when searching with Model::find().
 * 
 * @param object $model Model instance
 * @param boolean $queryData Information about current query: conditions, fields, etc.
 * @return boolean True if 'deleted' field doesn't exist (continue with hard delete)
 */		
	function beforeFind(Model $model, $queryData) {
		if(is_string($queryData['conditions'])) { $queryData['conditions'] = array($queryData['conditions']); }
		$queryData = array_merge(array('isDeleted' => false), $queryData);
		# Pass isDeleted=>null to get all, true for just deleted, and false for just not deleted


		if ($this->settings[$model->name]['enabled'] && $model->hasField($this->field))
		{
			#$this->purge($model); # Clear if needed.
			# Causing infinite recursion, hmmm...
		}

		# Automagic filtering
		# if admin and view ('first'), show. otherwise, filter out.

		if($model->hasField($this->field)) {
		#if ($this->settings[$model->name]['enabled'] && $model->hasField($this->field) && isset($queryData['isDeleted'])) {
			if(!Configure::read('in_admin') || !in_array($model->findQueryType, array('first')))
			#if ($queryData['isDeleted']) {
			{
				$queryData['conditions'][$model->alias . '.' . $this->field] = $this->_notDeleted($model);
			}
			#$queryData['conditions']['NOT'][$model->alias . '.' . $this->field] = $this->_notDeleted($model);
		}
		return $queryData;
	}
/**
 * Disables soft deletable behavior for a specific model.
 * 
 * @param object $model Model instance
 * @return void
 */
	function disableDeletable(&$model) {
		$this->settings[$model->name]['enabled'] = false;
	}
/**
 * Enables soft deletable behavior for a specific model.
 * 
 * @param object $model Model instance
 * @return void
 */
	function enableDeletable(&$model) {
		$this->settings[$model->name]['enabled'] = true;
	}

	function isEnabledDeletable(&$model)
	{
		return !empty($this->settings[$model->name]['enabled']);
	}

// Flush soft deleted records....
function purge(&$model, $days = 30, $cascade = true)
{
	$purged = false;
	if($model->hasField($this->field))
	{
		if($model->_schema[$this->field]['type'] == 'datetime')
		{
			# Past threshold...
			$purged = $model->deleteAll(array("{$this->field} <= NOW() - INTERVAL $days DAYS"), $cascade);
		} else { # Do all.
			$purged = $model->deleteAll(array("{$this->field}"=>"1"), $cascade);
		}
	}

	return $purged;
}
/**
 * Restore callbacks to be overridden by models.
 * 
 */	
	function beforeRestore() {
		return true;
	}
	function afterRestore() {
	}
/**
 * Cascades restore on dependent models with a datetime deleted field and records
 *  that were deleted on or after the parent record.
 *  
 *
 * @param string $id ID of record that was deleted
 * @param boolean $cascade Set to true to delete records that depend on this record
 * @return void
 * @access protected
 */
	function _restoreDependent($model, $id, $cascade, $threshold) {
		if (!empty($model->__backAssociation)) {
			$savedAssociatons = $model->__backAssociation;
			$model->__backAssociation = array();
		}
		foreach (array_merge($model->hasMany, $model->hasOne) as $alias => $params) {
			if ($params['dependent'] === true && $cascade === true) {
				$association =& $model->$alias;
				if ($this->_isDeletable($association) && $association->_schema[$this->field]['type'] != 'datetime') {
					continue;
				}
				$conditions = array(
					$association->escapeField($params['foreignKey']) => $id,
					$association->escapeField($this->field) . ' >=' => $threshold
				);
				if ($params['conditions']) {
					$conditions = array_merge((array)$params['conditions'], $conditions);
				}
				if (array_key_exists($association->alias . '.' . $this->field, $conditions)) {
					unset($conditions[$association->alias . '.' . $this->field]);
				}
				
				$association->recursive = -1;
				$records = $association->find('all', array(
					'isDeleted' => null, 
					'conditions' => $conditions, 
					'fields' => $association->primaryKey
				));
				
				if (!empty($records)) {
					foreach ($records as $record) {
						$association->restore($record[$association->alias][$association->primaryKey]);
					}
				}
			}
		}
		if (isset($savedAssociatons)) {
			$model->__backAssociation = $savedAssociatons;
		}
	}
/**
 * Evaluate if the model setup for the soft deletable behavior.
 * 
 * @param object $model Model instance
 * @return boolean
 * @access protected
 */	
	function _isDeletable(&$model) {
		return in_array('SoftDeletable.SoftDeletable', (array)$model->actsAs) && $model->hasField($this->field);
	}
/**
 * Determine the value to set in the deleted field to based on column type in schema.
 * 
 * @param object $model Model instance
 * @return mixed
 * @access protected
 */	
	function _deleted(&$model) {
		if ($model->_schema[$this->field]['type'] == 'datetime') {
			return date('c');
		}
		return true;
	}	
	function _notDeleted(&$model) {
		if ($model->_schema[$this->field]['type'] == 'datetime') {
			return null;
		}
		return false;
	}
/**
 * Update the soft deletable field with a new value.
 * 
 * @param object $model Model instance
 * @param numeric $value New value for field
 * @access protected
 */	
	function _update(&$model, $value) {
		return $model->save(
			array($model->alias => array($this->field => $value)), 
			array('validate' => false, 'callbacks' => true)
		);
	}	

	# Sets 'deleted' field
	function trash($model, $id)
	{
		# MUST reset $model data, since we might call read() before this... extra fields throws off validation...
		$model->data = false;
		$model->id = $id;
		$data = array($model->primaryKey=>$id);
		if($model->hasField("deleted_by"))
		{
			$data['deleted_by'] = Configure::read("user_id");
		}
		if($this->can_soft_delete($model))
		{
			error_log("SOFT DEL");
			$data['deleted'] = date('Y-m-d H:i:s');
			$model->softDeleted = true;
			$model->set($data);
			error_log("DATA INTERNAL=".print_r($model->data,true));

			# XXX for some reason it's setting ALL the fields, and this is causing issues with uername/password (thinking it's duplicating/empty, etc)
			#
			if(!($rc = $model->save()))
			{
				error_log(print_r($model->validationErrors,true));
			}
			return $rc;
		} else { # No cchoice but delete...
			$model->softDeleted = false;
			error_log("HARD DEL");
			return $model->hardDelete($id);
		}
	}

	function can_soft_delete($model)
	{
		return $model->hasField("deleted");
	}

	function restore($model, $id)
	{
		$orig = $model->read(null, $id);

		$model->data = false; # Clear for validator.

		$data = array($model->primaryKey=>$id);
		if($this->can_soft_delete($model))
		{
			$data['deleted'] = null;
			$model->set($data);
			if(!($rc = $model->save()))
			{
				error_log(print_r($model->validationErrors,true));
			}
			return $rc;
		} else { # can't restore.
			return false;
		}
	}

	function hardDelete($model, $id)
	{
		error_log("DELET_ALL TRY {$model->primaryKey}=$id");
		return $this->deleteAll(array("{$model->alias}.{$model->primaryKey}"=>$id), true, false);
	}
}
