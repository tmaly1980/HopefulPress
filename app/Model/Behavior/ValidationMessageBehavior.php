<?php 
/** 
 * ValidationMessage behavior class. 
 * 
 * Display automatic validation message for core validation rules 
 * 
 * PHP versions 5 
 * 
 * 
 * Licensed under The MIT License 
 * Redistributions of files must retain the above copyright notice. 
 * 
 * @author        Md. Rayhan Chowdhury <ray@raynux.com> 
 * @copyright     Copyright 2010, Raynux.com. 
 * @package       cake 
 * @subpackage    cake.app.model.behaviors 
 * @since         CakePHP v 1.2.0.4487 
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php) 
 */ 

/** 
 * ValidateMessage Behavior 
 * 
 * @package       cake 
 * @subpackage    cake.appe.model.behaviors 
 */ 
class ValidationMessageBehavior extends ModelBehavior { 

        protected $_messages = array( 
                                        'notempty'      => "This field can't be left blank.", 
                                        'between'       => "Must be between %s and %s characters long.", 
                                        'email'         => "Please provide a valid email address.", 
                                        'alphanumeric'  => "Only letters and numbers allowed.", 
                                        'boolean'       => "Only boolean data allowed.", 
                                        'cc'            => "The credit card number you supplied was invalid.", 
                                        'comparison'    => 'The data you supplied are incorrect.', 
                                        'date'          => "Please provide date in correct format.", 
                                        "decimal"       => "Numeric value required.", 
                                        "equalto"       => "Value must be equal to %s", 
                                        "extension"     => "Please upload a valid file with supported extensions.", 
                                        "ip"            => "Please supply a valid IP address.", 
                                        "isunique"      => "This value has already been taken.", 
                                        "minlength"     => "Must be at least %s characters long.", 
                                        "maxlength"     => "Must be no larger than %s characters long.", 
                                        "money"         => "Please supply a valid monetary amount.", 
                                        "multiple"      => "Please select multiple options as specified.", 
                                        "inlist"        => "Invalid data provided, please try again.", 
                                        "numeric"       => "Only numeric value allowed.", 
                                        "phone"         => "Phone number is invalid.", 
                                        "postal"        => "Postal code is invalid.", 
                                        "range"         => "Please enter a value between %s and %s.", 
                                        "ssn"           => "Social Security Number is not valid.", 
                                        "url"           => "Please provide a valid URL.", 
                                        "slug"          => "Only letters, numbers, dash and underscore characters allowed", 
                                    ); 

        /** 
         * Get the automatice validation message 
         *  
         * @param array $rule, string or array 
         * @return string internationalized 
         */ 
        function _autoMessage($rule) { 
            $rule[0] = $this->_messages[strtolower($rule[0])]; 
            return __(call_user_func_array('sprintf', $rule), true); 
        } 

        /** 
         * Attach automatic message for each validation 
         * 
         * @return void 
         * @access public 
         */ 
    function  beforeValidate(Model $model, $options = array()) { 
            parent::beforeValidate($model, $options); 
             
            if (!empty($model->validate)) { 
                foreach ($model->validate as $fieldName => &$ruleSet) { 

                    // for single rule 
                    if (is_string($ruleSet) && !empty($this->_messages[strtolower($ruleSet)])) { 
                            $ruleSet = array('rule' => $ruleSet, 'message' => $this->_autoMessage(array($ruleSet))); 
                    } 

                    // for array 
                    if (is_array($ruleSet)) { 
                        if (isset($ruleSet['rule'])) { 
                            if (!isset($ruleSet['message'])) { 
                                $rule = is_string($ruleSet['rule'])? array($ruleSet['rule']) : $ruleSet['rule']; 
                                if (is_string($rule[0]) && !empty($this->_messages[strtolower($rule[0])])) { 
                                    $ruleSet['message'] = $this->_autoMessage($rule); 
                                }    
                            } 
                        } else { 
                            // for multiple rules per field 
                            foreach ($ruleSet as $index => $rule) { 
                                if (!isset($rule['message'])) { 
                                    $rule = is_string($rule['rule']) ? array($rule['rule']) : $rule['rule']; 
                                    if (is_string($rule[0]) && !empty($this->_messages[strtolower($rule[0])])) { 
                                        $ruleSet[$index]['message'] = $this->_autoMessage($rule); 
                                    } 
                                } 
                            } 
                        } 
                    } 
                } 
            } 
    } 
} 
