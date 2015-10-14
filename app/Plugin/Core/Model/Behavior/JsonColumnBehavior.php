<?php

############# IF using on belongsTo, we need to declare in main model's  actsAs also, since only main model has  behavior's afterFind() fired.
/**
 * Be able to easily save and retrieve PHP arrays to/from a database's column
 *
 * @author Lucas Pelegrino <lucas.wxp@gmail.com>
 */
class JsonColumnBehavior extends ModelBehavior {
/**
 * The default options for the behavior
 *
 * @var array
 * @access public
 */
    public $_defaults = array(
        'fields' => array()
    );
    public $settings = array();

/**
 * Setup the behavior.
 *
 * @param object $model Reference to model
 * @param array $settings Settings
 * @return void
 * @access public
 */
    public function setup(Model $model, $settings = array()) {
        $this->settings[$model->alias] = array_merge($this->_defaults, $settings);
	error_log("JSONSETUP {$model->alias}");
    }

/**
 *
 * @param object $model Reference to model
 * @access public
 */
    public function beforeSave(Model $model, $options = array()) {
        foreach($this->settings[$model->alias]['fields'] as $field){
            if(isset($model->data[$model->alias][$field]))
                $model->data[$model->alias][$field] = $this->_encode($model->data[$model->alias][$field]);
        }
            return true;
    }


/**
 *
 * @param object $model Reference to model
 * @access public
 */
    public function afterFind(Model $model, $results, $primary = false) {
    	error_log("AFT#ER_FIND={$model->alias}");
        foreach($results as $i => &$res){
	# Since this won't get called on belongsTo/etc associated models, we need to do the work FOR them...
            foreach($this->settings[$model->alias]['fields'] as $field){
                if(isset($res[$model->alias][$field])) {
                    $res[$model->alias][$field] = $this->_decode($res[$model->alias][$field]);
		}
            }
	    if(!empty($model->belongsTo))
	    {
	    	foreach($model->belongsTo as $m=>$cfg)
		{
			if(is_numeric($m)) { $m = $cfg; }
			if(!empty($this->settings[$m]) && !empty($res[$m])) # FILTER THEM TOO...
			{
				error_log("FILTERING $m");
            			foreach($this->settings[$m]['fields'] as $field) {
                			if(isset($res[$m][$field])) {
                    				$res[$m][$field] = $this->_decode($res[$m][$field]);
					}
				}
			}
		}
	    }
        }
        return $results;
    }

/**
 * Encode json
 *
 * @param $data
 * @return mixed
 */
    protected function _encode($data){
        return json_encode($data);
    }

/**
 * Decode json
 *
 * @param $data
 * @return mixed
 */
    protected function _decode($data){
        $decode = json_decode($data,true); # Recursively, as arrays
	return $decode;
        #return is_object($decode) ? get_object_vars($decode) : $decode;
    }
}
?>
