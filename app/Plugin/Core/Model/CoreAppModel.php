<?php

App::uses('Model', 'Model');

class CoreAppModel extends Model {
	var $findMethods = array('field'=>true,'recent'=>true,'upcoming'=>true,'previous'=>true,'latest'=>true,'urllist'=>true,'first_id'=>true);

	var $existsCallbacks = array('Sluggable.Sluggable'); # Callbacks to still call during exists() so we find proper keys for INSERT/UPDATE

	function __construct($id = false, $table = null, $ds = null) {
        	parent::__construct($id, $table, $ds);

		#$this->checkBrokenModel();
		$this->fixPseudoFields();
	}
	#####################################################

	# Generic DROPDOWNS
	var $yesno = array('','Yes','No');

	function dropdown($k) # Takes list var and duplicates values as keys for form dropdowns...
	{
		return array_combine($this->{$k},$this->{$k});
	}

	#################

	function page_title() { 
		return Inflector::singularize(Inflector::humanize(Inflector::underscore($this->name))); 
	}

	function thingVar($name = null)
	{
		if(empty($name)) { $name = $this->name; }
		return Inflector::variable($name);
	}

	function thingVars($name = null)
	{
		return Inflector::pluralize($this->thingVar($name));
	}


	function thing($name = null)
	{
		if(empty($name)) { $name = $this->name; }
		return strtolower(Inflector::singularize(Inflector::humanize(Inflector::underscore($name))));
	}

	function things($name = null)
	{
		if(empty($name)) { $name = $this->name; }
		return strtolower(Inflector::pluralize(Inflector::humanize(Inflector::underscore($name))));
	}

	function checkBrokenModel()
	{
		# Handle broken calls to models that autoload the default class.
        	if ($this->toString() == 'AppModel' && !empty($_SERVER['HTTP_HOST'])) { # CLI ok
			echo "COULD NOT FIND FILE FOR MODEL=".$this->name.". Missing plugin prefix?\n\n";
            		debug(Debugger::trace());
            		exit;
        	}
    	}

	function fixPseudoFields()
	{
		# ALSO FIX %ALIAS% in order
    		$this->order = preg_replace("/%ALIAS%/", $this->alias, $this->order);

		# Fix virtualFields so %ALIAS% gets mapped correctly...
		foreach($this->virtualFields as $name=>$expr)
		{
    			$this->virtualFields[$name] = preg_replace("/%ALIAS%/", $this->alias, $expr);
		}

		# This cant be in a behavior because Updates wont call behavior on associated model.
		if($this->hasField("url"))
		{
			$this->virtualFields["idurl"] = "IF(({$this->alias}.url IS NULL OR {$this->alias}.url = ''), {$this->alias}.id, {$this->alias}.url)";
		}
	}

	function first($cond = array())
	{
		return $this->find('first',array('conditions'=>$cond));
	}

	function count($cond = array())
	{
		# Can set ->id before, or pass as param
		if (is_numeric($cond)) { # ID itself.
			$id = $cond;
			$cond = array("{$this->alias}.{$this->primaryKey}"=>$id);
		} else if(!is_array($cond) && $this->hasField("url") && preg_match("/^[\/a-zA-Z0-9_-]+$/", $cond)) { # url field... && !preg_match("/\s+/", $cond) && $this->hasField("url")) { # is URL field...
			$cond = array("{$this->alias}.url"=>$cond);
		}
		if(empty($cond) && !empty($this->id)) { 
			$cond = array($this->alias.".".$this->primaryKey=>$this->id);
		}
		$count = $this->find('count',array('conditions'=>$cond,'recursive'=>-1));
		return $count;
	}

	#function count($conditions = array()) { return $this->findCount($conditions); }

	# For random data generation
	function random($conditions = array(), $opts = array())
	{
		return $this->find('first',array('conditions'=>$conditions,'order'=>"RAND()",'limit'=>1));
	}

	function fieldrandom($field, $conditions = array())
	{
		return $this->field($field, $conditions,"RAND()");
	}

	#####################
	function field($name, $conditions = null, $order = null)
	{
		# Safely retrieve singleton id, if such.
		if($this->Behaviors->enabled("Singleton") && empty($this->id)) # Load default.
		{
			$this->id = $this->first_id();
		}
		return parent::field($name,$conditions,$order);
	}

	function fields($field, $conditions = null, $options = array())
	{ # Get value of single field from many records, as a list.
		$options['fields'] = array($field);
		$options['conditions'] = $conditions;
		if(!isset($options['recursive'])) { $options['recursive'] = -1; } # SAVE energy.
		return $this->find('field', $options);
	}
	function _findField($state, $query, $results = array()) # Extract just a single field out of all these records.
	{
		if($state == 'after')
		{
			$field = $query['fields'][0];
			list($model,$field) = pluginSplit($field);
			if(empty($model)) { $model = $this->alias; }
			$field_values = empty($results) ? $results : Set::extract($results, "{n}.$model.$field");
			return $field_values;
		} else {
			#error_log("Q=".print_r($query,true));
			return $query;
		}
	}

	function _findLatest($state, $query, $results = array()) 
	{ # Only most recent record...
		if($state == 'after')
		{
			return !empty($results) ? $results[0] : array();
		} else {
			$query['limit'] = 1;
			$query['order'] = "{$this->alias}.created DESC";
			#pr(Debugger::trace());
			#exit(0);
			return $query;
		}
	}
	function _findRecent($state, $query, $results = array()) 
	{
		if($state == 'after')
		{
			return $results;
		} else {
			if(!isset($query['limit']))
			{
				$query['limit'] = 5;
			}
			$query['order'] = ($this->hasField("published") ? "{$this->alias}.published DESC, ":"")
				.($this->hasField("start_date") ? "{$this->alias}.start_date DESC, ":"")
				. "{$this->alias}.created DESC";
			return $query;
		}
	}
	function _findUpcoming($state, $query, $results = array()) 
	{
		if($state == 'after')
		{
			return $results;
		} else {
			#$query['limit'] = 5;
			$query['conditions'] = "(DATE({$this->alias}.start_date) >= DATE(NOW()) OR DATE({$this->alias}.end_date) >= DATE(NOW()))"; # FUTURE
						# *** NEEDS PARENTHESES AROUND THE ENDS SO WE DONT CHANGE LOGIC
			$query['order'] = "{$this->alias}.start_date ASC, {$this->alias}.end_date ASC";
			return $query;
		}
	}
	function _findPrevious($state, $query, $results = array()) 
	{
		if($state == 'after')
		{
			return $results;
		} else {
			#$query['limit'] = 5;
			$query['conditions'] = "(DATE({$this->alias}.start_date) < DATE(NOW()) OR DATE({$this->alias}.end_date) < DATE(NOW()))"; # FUTURE
						# *** NEEDS PARENTHESES AROUND THE ENDS SO WE DONT CHANGE LOGIC
			$query['order'] = "{$this->alias}.start_date ASC, {$this->alias}.end_date ASC";
			return $query;
		}
	}

	function findAll($conditions = array(), $fields = null, $order = null)
	{
		return $this->find('all', array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order));
	}

	function findCount($conditions = array())
	{
		return $this->find('count', array('conditions'=>$conditions));
	}

	# Sorting logic for sortable lists should be in Sortable behavior....

	function loadModel($model)
	{
		if(!empty($this->{$model})) { return; } # ALready loaded.

		App::uses($model, "Model");
		$this->{$model} = new $model();
	}

	function schema_fields()
	{
		return array_keys($this->schema());
	}

	function _findUrllist($state, $query, $results = array()) # nav friendly list.
	{
		if($state == 'after')
		{
			return Set::combine($results, "{n}.{$this->alias}.idurl", "{n}.{$this->alias}.{$this->displayField}");
		} else {
			$query['fields'] = array("{$this->alias}.idurl", "{$this->alias}.{$this->displayField}");
			return $query;
		}
	}

	function sqlDump()
	{
		#$dbo =& ConnectionManager::getDataSource($this->useDbConfig);
		#$dbo->showLog();
		$log = $this->getDataSource()->getLog(false,false);
		print_r($log);
	}

	function first_id($cond = array()) # Get ID for first record found, especially singleton.
	{ # BUT MIGHT NOT BE A SINGLETON! may reference by some other unique field.
		return $this->find('first_id', array('conditions'=>$cond));
		#$this->id = false;
		#return $this->field($this->primaryKey);
	}

	function _findFirst_id($state, $query, $results = array()) # Extract just a single field out of all these records.
	{
		if($state == 'after')
		{
			return !empty($results[0]) ? $results[0][$this->alias][$this->primaryKey] : null;
		} else {
			$query['limit'] = 1;
			$query['fields'] = array("{$this->alias}.{$this->primaryKey}");
			return $query;
		}
	}

	# Need to replace exists() so can be hacked by Sluggable (url work as well as id)o
        public function exists($id = null) {
                if ($id === null) {
                        $id = $this->getID();
                }

                if ($id === false) {
                        return false;
                }

                return (bool)$this->find('count', array(
                        'conditions' => array(
                                $this->alias . '.' . $this->primaryKey => $id
                        ),
                        'recursive' => -1,
                        'callbacks' => $this->existsCallbacks # HERE
                ));
        }


	function errorString() # Get validation error string.
	{
		$error = array();
		foreach($this->validationErrors as $m=>$a)
		{
			if(is_array($a))
			{
				foreach($a as $k=>$v)
				{
					if(is_array($v)) { $v = join(". ", $v); }
					error_log("SAVE ERROR: $m.$k = $v");
					$error[] = $v;
				}
			} else {
				$error[] = $a;
			}
		}

		return join(". ", $error);
	}

	# Binding conveniences...
	function setBelongsTo($model, $opts = array())
	{
		if($model === null)
		{
			$this->unbindModel(array('belongsTo'=>array_keys($this->belongsTo)));
		} else if(!empty($opts)) {
			$this->bindModel(array('belongsTo'=>array($model=>$opts)), false);
		} else if(is_array($model)) {
			$this->bindModel(array('belongsTo'=>$model), false);
		} else {
			$this->bindModel(array('belongsTo'=>array($model)), false);
		}
		# Assumes default class and key naming...
		return $this;
	}

	function setHabtm($model,$opts=array())
	{
		return $this->setHasAndBelongsToMany($model,$opts);
	}
	function setHasAndBelongsToMany($model,$opts = array())
	{
		if($model === null)
		{
			$this->unbindModel(array('hasAndBelongsToMany'=>array_keys($this->hasAndBelongsToMany)));
		} else if(is_array($model)) {
			$this->bindModel(array('hasAndBelongsToMany'=>$model), false);
		} else if(!empty($opts)) {
			$this->bindModel(array('hasAndBelongsToMany'=>array($model=>$opts)), false);
		} else {
			$this->bindModel(array('hasAndBelongsToMany'=>array($model)), false);
		}
		return $this;
	}

	function setHasMany($model, $opts=array())
	{
		if($model === null)
		{
			$this->unbindModel(array('hasMany'=>array_keys($this->hasMany)));
		} else if(is_array($model)) {
			$this->bindModel(array('hasMany'=>$model), false);
		} else if(!empty($opts)) { 
			$this->bindModel(array('hasMany'=>array($model=>$opts)), false);
		} else {
			$this->bindModel(array('hasMany'=>array($model)), false);
		}
		return $this;
	}
	function setHasOne($model,$opts=array())
	{
		if($model === null)
		{
			$this->unbindModel(array('hasOne'=>array_keys($this->hasOne)));
		} else if(is_array($model)) {
			$this->bindModel(array('hasOne'=>$model), false);
		} else if(!empty($opts)) { 
			$this->bindModel(array('hasOne'=>array($model=>$opts)), false);
		} else {
			$this->bindModel(array('hasOne'=>array($model)), false);
		}
		return $this;
	}

	###############################
	function random_chars($length = 8) # Random codes, whether password, unique filenames, registration code, etc...
	{
		$chars = array();
		for ($i = ord('a'); $i < ord('z'); $i++)
		{
			$chars[] = chr($i);
		}
		for ($i = ord('A'); $i < ord('Z'); $i++)
		{
			$chars[] = chr($i);
		}
		for ($i = ord('0'); $i < ord('9'); $i++)
		{
			$chars[] = chr($i);
		}

		shuffle($chars); # randomize.

		$code = "";
		for ($ix = 0; $ix < $length; $ix++)
		{
			$code .= $chars[ rand(0, count($chars)-1) ];
		}

		return $code;
	}

	function dates($key = null) # Get useful dates of points in time.
	{
		# Day name will reference next week's if that day has already passed in the week (ie sunday from wednesday is shown as NEXT sunday, but saturday is THIS saturday)
		$thissunday = date("Y-m-d", strtotime(sprintf("-%d days", date("w"))));
		$thissaturday = date("Y-m-d 23:59:59", strtotime("$thissunday +6 day"));

		$lastsunday = date("Y-m-d", strtotime("$thissunday -1 week"));
		$lastsaturday = date("Y-m-d 23:59:59", strtotime("$lastsunday +6 day"));

		#echo "THIS YER/WK=$thisyear, $thisweek; thissun=$thissunday, SAT=$thissaturday, last=$lastsunday, $lastsaturday";

		$thismonthstart = date("Y-m-01", strtotime('this month'));
		$nextmonthstart = date("Y-m-01", strtotime('next month'));
		$thismonthend = date("Y-m-d 23:59:59", strtotime("$nextmonthstart - 1 day"));

		$lastmonthstart = date("Y-m-01", strtotime('last month'));
		$lastmonthend = date("Y-m-d 23:59:59", strtotime("$thismonthstart - 1 day"));

		$dates = array(
			'thissunday'=>$thissunday,
			'thissaturday'=>$thissaturday,
			'lastsunday'=>$lastsunday,
			'lastsaturday'=>$lastsaturday,
			'thismonthstart'=>$thismonthstart,
			'nextmonthstart'=>$nextmonthstart,
			'thismonthend'=>$thismonthend,
			'lastmonthstart'=>$lastmonthstart,
			'lastmonthend'=>$lastmonthend,
		);
		$this->dates = $dates;

		return !empty($key) ? "'".$dates[$key]."'" : $dates;
	}

	function escapedate($date) # Only dates (or datetimes). Let expressions remain untouched, and so our queries dont need quotes
	{
		return preg_match("/^\d+-\d+-\d+(\s+[0-9:]+)*$/", $date) ? "'$date'" : $date;
	}

	function read($fields = null, $id = null) # Fix so read() w/o params does find('first') (and not id IS NULL)
	{
		if($id === null && $this->id === null)
		{
			return $this->find('first', array('fields'=>$fields));
		} else {
			return parent::read($fields, $id);
		}
	}

	# VALIDATION SETTING, stored in groups in Model::$validateModes
	function validateMode($modeset)
	{
		$validator = $this->validator();
		#$fields = $validator->getField();
		
		if($modeset === false) # RESET
		{
			# Clear out "optional" stuff, but keep permanent stuff.
			foreach($this->validateModes as $mode=>$fieldset)
			{
				foreach($fieldset as $field=>$ruleset)
				{
					$validator->remove($field);
				}
			}
		} else {
			if(!is_array($modeset)) { $modeset = array($modeset); }

			foreach($modeset as $mode)
			{
				$ruleset = $this->validateModes[$mode];
				foreach($ruleset as $field=>$rule)
				{
					$validator->add($field, $rule);
				}
			}
		}

		return $validator;
	}

	function fieldType($field)
	{
		$schema = $this->schema($field);
		return !empty($schema['type']) ? $schema['type'] : null;
	}

	function h2a($h)
	{
		$a = array();
		foreach($h as $k=>$v)
		{
			$a[] = array($k,$v);
		}
		return $a;
	}

	# HIERARCHY STUFF
	function treelist($cond = array(), $level = 0, $delim = '&nbsp;&nbsp;&nbsp;') # Dropdown ready
	{
		#error_log("COND=".print_r($cond,true));
		$tree = $this->tree($cond, 
			array("{$this->alias}.id",
				"{$this->alias}.parent_id",
				"{$this->alias}.title")
			);
		#echo "TREE=".print_r($tree,true)."<br/>\n";
		$t2nl = $this->tree2nestedlist($tree, $level, $delim);
		#echo "T2NL=".print_r($t2nl,true);
		#exit(0);
		return $t2nl;
	}
	function tree2nestedlist($tree, $level = 0, $delim = '&nbsp;&nbsp;&nbsp;')
	{
		#error_log("NESTING (l=$level)=".print_r($tree,true));
		$prefix = "  ".str_repeat($delim, $level);
		$options = array();
		foreach($tree as $item)
		{
			$id = $item[$this->alias]['id'];
			$title = $item[$this->alias]['title'];


			#echo "ID $id => $title<br/>\n";

			$children = !empty($item['children']) ?
				$item['children'] : array();
			$options[$id] = $prefix . " ". $title;

			#error_log("$prefix ($id) => $title");

			#error_log("$prefix CHILDREN = ".print_r($children,true));

			$childoptions = $this->tree2nestedlist($children, $level+1, $delim);
			#echo "FOR $id, CHOPS=".print_r($childoptions,true)."<br/>\n";
			#error_log("$prefix +CHOPS=".print_r($childoptions,true));
			foreach($childoptions as $chid=>$chtitle)
			{
				$options[$chid] = $chtitle;
			}
			#$options = array_merge($options, $childoptions);
		}
		#error_log("OPSEND=".print_r($options,true));
		return $options;
	}

	function tree($cond = array(), $fields = null) # Full data, for treed list page
	{
		if(empty($fields))
		{
			$fields = array('*');
		}
		$children = array();
		# Get full list, then turn into hierarchical list.
		$entries = $this->find('all', array('conditions'=>$cond,
			'fields'=>$fields,
			'order'=>$this->hasField("ix") ? array(#"{$this->alias}.parent_id ASC",
				"{$this->alias}.ix IS NULL", # ix set first.
				"{$this->alias}.ix ASC",
				"{$this->alias}.id ASC") : "{$this->alias}.created ASC"
			)
		);

		$tree = array(); # Top level.
		$children = array();

		foreach($entries as $entry)
		{
			$id = $entry[$this->alias]['id'];
			$parent_id = $entry[$this->alias]['parent_id'];

			if(empty($parent_id)) # Top level, no parent.
			{
				$tree[$id] = $entry;
			} else { # Children of some other entry, put aside.
				if(empty($children[$parent_id]))
				{
					$children[$parent_id] = array();
				}
				$children[$parent_id][] = $entry;
			}
		}
		#error_log("CHILDREN=".print_r($children,true));
		#return $tree; # top only

		foreach($children as $parent_id=>$childlist)
		{ # Add children to an item's data.
			if(empty($tree[$parent_id]))
			{
				foreach($childlist as $child)
				{
					$tree[$child[$this->alias]['id']] = $child;
				}

			} else {
				$tree[$parent_id]['children'] = $childlist;
				#error_log("ADDING CHILD TO$parent_id=".print_r($childlist,true));

			}
		}

		return $tree;
	}

	function free_orphans($key = 'parent_id') # move children with deleted parents to top-level
	{
		# Get unassociated dummy model.
		$class = get_class($this);

		$model = new $class(array(
			'table'=>$this->useTable,
			'ds'=>$this->useDbConfig,
			'name'=>$this->name,
			'alias'=>$this->alias,
			'generic'=>true, # So we dont trip!
		));

		$model->bindModel(array('belongsTo'=>array(
			'Parent'=>array(
				'className'=>$this->name,
				'foreignKey'=>$key,
			)
		)));

		$orphans = $model->find('all',array('fields'=>array("{$this->alias}.id"),
				'conditions'=>"Parent.id IS NULL AND {$this->alias}.$key IS NOT NULL"
		));

		$orphan_ids = Set::extract($orphans, "{n}.{$this->alias}.id");

		if(!empty($orphan_ids))
		{
			$this->updateAll(array("{$this->alias}.$key"=>null), array("{$this->alias}.id"=>$orphan_ids));
		}
	}

    /**
     * Get Enum Values
     * Snippet v0.1.3
     * http://cakeforge.org/snippet/detail.php?type=snippet&id=112
     *
     * Gets the enum values for MySQL 4 and 5 to use in selectTag()
     */
    function getEnumValues($columnName=null, $respectDefault=false, $optionalValue = false)
    {
        if ($columnName==null) { return array(); } //no field specified


        //Get the name of the table
        $db = ConnectionManager::getDataSource($this->useDbConfig);
        $tableName = $db->fullTableName($this, false);

        //Get the values for the specified column (database and version specific, needs testing)
        $result = $this->query("SHOW COLUMNS FROM {$tableName} LIKE '{$columnName}'");

        //figure out where in the result our Types are (this varies between mysql versions)
        $types = null;
        if     ( isset( $result[0]['COLUMNS']['Type'] ) ) { $types = $result[0]['COLUMNS']['Type']; $default = $result[0]['COLUMNS']['Default']; } //MySQL 5
        elseif ( isset( $result[0][0]['Type'] ) )         { $types = $result[0][0]['Type']; $default = $result[0][0]['Default']; } //MySQL 4
        else   { return array(); } //types return not accounted for

        //Get the values
        $values = explode("','", preg_replace("/(enum)\('(.+?)'\)/","\\2", $types) );

        if($respectDefault){
                $assoc_values = array("$default"=>Inflector::humanize($default));
                foreach ( $values as $value ) {
                        if($value==$default){ continue; }
                        $assoc_values[$value] = Inflector::humanize($value);
                }
        }
        else{
                $assoc_values = array();
		if ($optionalValue)
		{
			if ($optionalValue === true)
			{
				$assoc_values[''] = Inflector::humanize("None");
			} else if ($optionalValue !== false) {
				$assoc_values[''] = Inflector::humanize($optionalValue);
			}
		}
                foreach ( $values as $value ) {
                        $assoc_values[$value] = Inflector::humanize($value);
                }
        }

        return $assoc_values;

    } //end getEnumValues

    function getSetValues($columnName=null, $respectDefault=false, $optionalValue = false)
    {
        if ($columnName==null) { return array(); } //no field specified


        //Get the name of the table
        $db = ConnectionManager::getDataSource($this->useDbConfig);
        $tableName = $db->fullTableName($this, false);


        //Get the values for the specified column (database and version specific, needs testing)
        $result = $this->query("SHOW COLUMNS FROM {$tableName} LIKE '{$columnName}'");

        //figure out where in the result our Types are (this varies between mysql versions)
        $types = null;
        if     ( isset( $result[0]['COLUMNS']['Type'] ) ) { $types = $result[0]['COLUMNS']['Type']; $default = $result[0]['COLUMNS']['Default']; } //MySQL 5
        elseif ( isset( $result[0][0]['Type'] ) )         { $types = $result[0][0]['Type']; $default = $result[0][0]['Default']; } //MySQL 4
        else   { return array(); } //types return not accounted for

        //Get the values
        $values = explode("','", preg_replace("/(set)\('(.+?)'\)/","\\2", $types) );

        if($respectDefault){
                $assoc_values = array("$default"=>Inflector::humanize($default));
                foreach ( $values as $value ) {
                        if($value==$default){ continue; }
                        $assoc_values[$value] = Inflector::humanize($value);
                }
        }
        else{
                $assoc_values = array();
		if ($optionalValue)
		{
			if ($optionalValue === true)
			{
				$assoc_values[''] = Inflector::humanize("None");
			} else if ($optionalValue !== false) {
				$assoc_values[''] = Inflector::humanize($optionalValue);
			}
		}
                foreach ( $values as $value ) {
                        $assoc_values[$value] = Inflector::humanize($value);
                }
        }

        return $assoc_values;

    } //end getSetValues

    
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $conditions = compact('conditions');
        if ($recursive != $this->recursive) {
            $conditions['recursive'] = $recursive;
        }
        unset( $extra['contain'] );
        $count = $this->find('count', array_merge($conditions, $extra));
       
            if (isset($extra['group'])) {
                $count = $this->getAffectedRows();
            }
       
        return $count;
    }

    # Also need to internally code comma joining arrays  so we dont call before json encode.
    function commaJoin()
    {
    	foreach($this->data[$this->alias] as $k=>$v)
	{
		if(is_array($v))
		{
			$this->data[$this->alias][$k] = join(",",$v);
		}
	}
	return true;
    }

    # We ALSO need this in beforeSave so we don't lose keys from CommaSeparated nonsense.
    function jsonEncode($keys=array())
    {
    	# comma-separate might be called before us.
    	error_log("JSON_ENCODE, DATA=".print_r($this->data[$this->alias],true));
	foreach($keys as $key)
	{
		if(!empty($this->data[$this->alias][$key]) && is_array($this->data[$this->alias][$key]))
		{
			$this->data[$this->alias][$key] = json_encode($this->data[$this->alias][$key]);
		}
	}
	error_log("JSON_COMPLETE=".print_r($this->data[$this->alias],true));
	return true;
    }

    # Behavior afterFind wont get called on belongsTo etc records...
    function jsonDecode($results, $keys=array())
    {
		foreach($results as &$res)
		{
			foreach($keys as $key)
			{
				if(!empty($res[$this->alias][$key]))
				{
					$data = json_decode($res[$this->alias][$key],true); # as hash. get_object_vars() doesnt recurse.

					if(!empty($data))
					{
						$res[$this->alias][$key] = $data;
					}
				}
			}

		}
		return $results;
    }



}
