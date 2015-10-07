<?php

App::import('Inflector');

class SluggableBehavior extends ModelBehavior {

    private $_settings = array();
    private $model = null;

    function setup(Model $model, $settings = array()) {
        $default = array(
            'fields' => 'title',
            'scope' => false,
            'conditions' => false,
            'slugfield' => 'url', # This needs to be changed to 'url', since was 'slug'. wtf.
            'separator' => '-',
            'overwrite' => false,
            'length' => 256,
            'lower' => true
        );

        $this->_settings[$model->alias] = (!empty($settings)) ? $settings + $default : $default;
	$this->model = $model;

	# Add unique validation requirement
	$model->validator()->add($this->_settings[$model->alias]['slugfield'], 'unique', array(
		'rule'=>'isPublishedUnique', 'message'=>'URL is already taken', 'allowEmpty'=>true
	));

	# Add idurl pseudoField
	if($model->hasField("url"))
	{
		$model->virtualFields['idurl'] = "IF({$model->alias}.url, {$model->alias}.url, {$model->alias}.id)";
	}
    }

    # CAN WE SOMEHOW TWEAK? just adding 'fields'
    function isPublishedUnique($model, $fields, $or = true) # 
    {
	if (!is_array($fields)) {
		$fields = func_get_args();
		if (is_bool($fields[count($fields) - 1])) {
			$or = $fields[count($fields) - 1];
			unset($fields[count($fields) - 1]);
		}
	}

	foreach ($fields as $field => $value) {
		if (is_numeric($field)) {
			unset($fields[$field]);

			$field = $value;
			$value = null;
			if (isset($model->data[$model->alias][$field])) {
				$value = $model->data[$model->alias][$field];
			}
		}

		if (strpos($field, '.') === false) {
			unset($fields[$field]);
			$fields[$model->alias . '.' . $field] = $value;
		}
	}

	if ($or) {
		$fields = array('or' => $fields);
	}


	########################

	$ids = array();

	# ADD draft_id
	if(!empty($model->data[$model->alias]['draft_id']))
	{
		$ids[] = $draft_id = $model->data[$model->alias]['draft_id'];
		$fields[$model->alias . '.draft_id !='] = $draft_id;
	}

	if (!empty($model->id)) {
		$ids[] = $model->id;
	}
	if(!empty($ids))
	{
		$fields[$model->alias . '.' . $model->primaryKey . ' NOT'] = $ids;
	}

	error_log("DATA=".print_r($model->data,true));

	$rc = !$model->find('count', array('conditions' => $fields, 'recursive' => -1));

	#$model->sqlDump();
	error_log("FIELDS ($rc)=".print_r($fields,true));

	return $rc;
    }

    function overwrite_slug(&$model, $overwrite = true) # In case they really want to.
    {
    	$this->_settings[$model->alias]['overwrite'] = $overwrite;
    }

    function slugify(Model $model, $data = null)
    {
    	if(!empty($data)) { $model->set($data); }

        $fields = (array) $this->_settings[$model->alias]['fields'];
        $scope = (array) $this->_settings[$model->alias]['scope'];
        $conditions = !empty($this->_settings[$model->alias]['conditions']) ? (array) $this->_settings[$model->alias]['conditions'] : array();
        $slugfield = $this->_settings[$model->alias]['slugfield'];
        $hasFields = true;

	# Ensure we have source fields
        foreach ($fields as $field) {
            if (!$model->hasField($field)) {
                $hasFields = false;
            }

            if (!isset($model->data[$model->alias][$field])) {
                $hasFields = false;
            }
        }

	# ALWAYS SLUG even if record exists, if slug is not set! get from db if existing record
	error_log("SLUGFIELD=$slugfield");

        if (!$hasFields || !$model->hasField($slugfield)) { error_log("CANNOT SLUGIFY, missing source ($field) or destination ($slugfield) fields"); return; }

		#error_log("SLUGGING");
            $toSlug = array();

            foreach ($fields as $field) {
                $toSlug[] = $model->data[$model->alias][$field];
            }
            
            $toSlug = join(' ', $toSlug);

            $slug = Inflector::slug($toSlug, $this->_settings[$model->alias]['separator']);
	    	#error_log("TEST $toSlug => $slug");

            if ($this->_settings[$model->alias]['lower']) {
                $slug = strtolower($slug);
            }

            if (strlen($slug) > $this->_settings[$model->alias]['length']) {
                $slug = substr($slug, 0, $this->_settings[$model->alias]['length']);
            }

            $conditions[$model->alias . '.' . $slugfield . ' LIKE'] = $slug . '%';

            if (!empty($model->id)) {
                $conditions[$model->alias . '.' . $model->primaryKey . ' !='] = $model->id;
            }

            if (!empty($scope)) {
                foreach ($scope as $s) {
                    if (isset($model->data[$model->alias][$s]) && !empty($model->data[$model->alias][$s])) {
                        $conditions[$model->alias . '.' . $s] = $model->data[$model->alias][$s];
                    }
                }
            }

            $sameUrls = $model->find('all', array(
	    	'fields'=>array($slugfield),
                'recursive'  => -1,
                'conditions' => $conditions
            ));

            $sameUrls = (!empty($sameUrls)) ?
                Set::extract($sameUrls, '{n}.' . $model->alias . '.' . $slugfield) :
                null;

            if ($sameUrls) {
                if (in_array($slug, $sameUrls)) {
                    $begginingSlug = $slug;
                    $index = 1;

                    while ($index > 0) {
                        if (!in_array($begginingSlug . $this->_settings[$model->alias]['separator'] . $index, $sameUrls)) {
                            $slug = $begginingSlug . $this->_settings[$model->alias]['separator'] . $index;
                            $index = -1;
                        }

                        $index++;
                    }
                }
            }

            if (!empty($model->whitelist) && !in_array($slugfield, $model->whitelist)) {
                $model->whitelist[] = $slugfield;
            }

	    #error_log("SLUG=$slug");

	    return $slug;
    }

    # support for 'idurl' pseudofield
    # support for read() to check url

    function beforeFind(Model $model, $query = array()) # Allow find by idurl when ->id set.
    {
	$cond = !empty($query['conditions']) ? (!is_array($query['conditions']) ? array($query['conditions']) : $query['conditions']) : array();
	#error_log("BEFORE_FIND {$model->alias}, COND=".print_r($cond,true));
    	if(!$model->hasField("url")) { return $query; }

	# If ID is non-numeric, check url field instead. (as long as it's not an ARRAY!)

	$pk = $model->primaryKey;
	$alias = $model->alias;

	if(isset($cond[$pk])) { $cond["$alias.$pk"] = $cond[$pk]; unset($cond[$pk]); } # Formalize.

    	if(!empty($cond["$alias.$pk"]) && !is_numeric($cond["$alias.$pk"]) && !is_array($cond["$alias.$pk"]))
	{
		$cond["$alias.url"] = $cond["$alias.$pk"];
		unset($cond["$alias.$pk"]);
		#unset($model->id); # In case we did this.
		#error_log("URL INSTEAD");
	}
	$query['conditions'] = $cond;
    	#error_log("QUERY_COND {$model->alias}=".print_r($query['conditions'],true));

	return $query;
    }

    function afterFind(Model $model, $results, $primary = false)
    {
    	# If ->id is non-numeric, get numeric id to save for access via ->id
	if(in_array($model->findQueryType, array('first','singleton')) && !empty($results[0][$model->alias]['id']))
	{
		$model->id = $results[0][$model->alias]['id'];
	}

	return $results;
    }

    # Work like find('list') except set field 0 to idurl
    function idurllist($model, $cond = array())
    { 
    	return $model->find('list', array(
		'fields'=>array('idurl',$model->displayField),
		'conditions'=>$cond
	));
    }


    // AUTO-POPULATE DEFAULT SLUG IF NOT USER SPECIFIED, ON CREATE

    function beforeSave(Model $model, $options = array()) {
        $slugfield = $this->_settings[$model->alias]['slugfield'];
	$slug_value = !empty($model->data[$model->alias][$slugfield]) ?
		$model->data[$model->alias][$slugfield] :
		(!empty($model->id) ? $model->field($slugfield) : null);

	if(empty($slug_value) || $this->_settings[$model->alias]['overwrite'])
	{
            $model->data[$model->alias][$slugfield] = $model->slugify($model->data);
	}

        return parent::beforeSave($model);
    }
}
