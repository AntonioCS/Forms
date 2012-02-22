<?php

/**
* Abstract class for elements
*/
abstract class element {

    /**
    * Element attributes
    *
    * @var array
    */
    protected $_attributes = array();

    /**
    * Label is the text that will be used in front of the control (in the table display it would be -> Label : Control)
    *
    * @var string
    */
    protected $_label = null;

    /**
    * Text to desribe the element
    *
    * @var string
    */
    protected $_description = null;

    /**
    * put your comment there...
    *
    * @var prefix
    */
    protected $_prefix = null;

    /**
    * put your comment there...
    *
    * @var sufix
    */
    protected $_sufix = null;

    /**
    * Parent element
    *
    * @var acs_element_container
    */
    protected $_parent = null;

    /**
    * Path to the template
    *
    * @var string
    */
    protected $tpl_path;

    /**
    * Errors of element
    *
    * @var array
    */
    protected $_errors = array();

    /**
    * Check to see if element has errors
    *
    * @return bool
    */
    public function hasErrors() {
        return !empty($this->_errors);
    }

    /**
    * Add an error msg
    *
    * @param string $error_msg
    */
    public function addError($error_msg) {
        $this->_errors[] = $error_msg;
    }

    /**
    * Return all error msgs
    *
    * @return array
    */
    public function getErrorMsgs() {
        return $this->_errors;
    }

	/**
	 *
	 * Element Contruct
	 *
	 * @param string $id - Set the id of the element if present
	 */
    public function __construct($id = null) {
		if ($id)
			$this->setId($id);
	}

    /**
    * This is to still be able to use methods such as setType or setId. With this I can use these shortcuts and still return the reference to the class
    * Also the get methods will still work
    *
    * @param string $funcname
    * @param mixed $funcargs
    */
    public function __call($funcname,$funcargs) {
        $fname = strtolower($funcname);
        $fpartial = substr($fname,0,3);
        if ($fpartial === 'set')
            return $this->setAttribute(substr($fname,3),$funcargs[0]);
        elseif ($fpartial === 'get')
            return $this->__get(substr($fname,3));
            //return $this->getAttribute(substr($fname,3));
        else
            throw new acs_exception('Unknown function call');
    }

    /**
    * Set the parent of this element
    *
    * @param acs_element_container $parent
    */
    public function setParent(acs_element_container $parent) {
        $this->_parent = $parent;
        return $this;
    }

    /**
    * Retrieve parent of element
    *
    */
    public function getParent() {
        return $this->_parent;
    }

    /**
     * Common setters
     * To bypass the __call method
     */
    public function setValue($value) { return $this->setAttribute('value',$value); }
    public function setType($value) { return $this->setAttribute('type',$value); }
    public function setId($value) { return $this->setAttribute('id',$value); }
    public function setClass($value) { return $this->setAttribute('class',$value); }
    public function setOnclick($value) { return $this->setAttribute('onclick',$value); }
    public function setStyle($value) { return $this->setAttribute('style',$value); }

    /**
    * Set element label
    *
    * @param string $label
    */
    public function setLabel($label) {
        $this->_label = $label;
        return $this;
    }

    /**
    * Set label on the left
    *
    */
    public function labelLeft() {
        $this->_label_position = 0;
    }

    /**
    * Set label on the right
    *
    */
    public function labelRight() {
        $this->_label_position = 1;
    }

    /**
    * Get element label
    *
    */
    public function getLabel() {
        return $this->_label;
    }

    public function setLabelPrefix($value) {
        $this->_label_prefix = $value;
        return $this;
    }

    public function getLabelPrefix() {
        return $this->_label_prefix;
    }

    /**
    * Set description of element
    *
    * @param string $desc
    */
    public function setDesc($desc) {
        $this->_description = $desc;
        return $this;
    }

    /**
    * Get description of element
    *
    */
    public function getDesc() {
        return $this->_description;
    }

    /**
    * Set prefix of element. Just before the element starts
    *
    * @param string $text
    */
    public function setPrefix($text) {
        $this->_prefix = $text;
        return;
    }

    /**
    * Get content of prefix
    *
    */
    public function getPrefix() {
        return $this->_prefix;
    }

    /**
    * Set sufix of element. Just after the element ends
    *
    * @param mixed $text
    */
    public function setSufix($text) {
        $this->_sufix = $text;
        return $this;
    }

    /**
    * Get sufix of element
    *
    */
    public function getSufix() {
        return $this->_sufix;
    }

    /**
    * Append a value to an attribute
    *
    * @param string $name
    * @param string $value
    */
    public function appendAttribute($name, $value) {
        if (isset($this->_attributes[$name]) && $this->_attributes[$name] != '') {
            $this->_attributes[$name] .= ' ' . $value;
        }
        else
            $this->setAttribute($name,$value);

        return $this;
    }

    /**
     * Set an attribute and return class instance
     *
     * @param string $attributename
     * @param string $value
     */
    public function setAttribute($attributename,$value) {
        $this->_attributes[$attributename] = $value;
        return $this;
    }

    /**
    * Retrieve the attribute
    *
    * @param string $varname
    */
    public function getAttribute($name) {
        if (isset($this->_attributes[$name]))
            return $this->_attributes[$name];
        return null;
    }

    /**
    * Method to return all attributes in format:
    *  attribute_name = "attribute_value"
    *
    * @return string
    */
    protected function getAttributes() {
        $attributes = array(''); //Add a space just in case there are no attributes
        foreach ($this->_attributes as $attributename => $attributevalue) {
            if ($attributevalue && $attributename[0] != '_') //attributes that start with _ will be used to do other things (like specifing validator, ways to sanitize etc)
                $attributes[] = $attributename . '="' . $attributevalue . '"';
        }

        //acs_log::getInstance()->showdebug()->debug($attributes);
        return implode(' ',$attributes);
    }

    /**
    * Method to determine if element has any attributes (will return true if it has)
    *
    */
    public function hasAttributes() {
        return !empty($this->_attributes);
    }

    /**
    * Method to merge an external array of attributes with the attributes of this element
    *
    * @param array $newattributes
    */
    public function mergeAttributes(array $newattributes) {
        $this->_attributes = array_merge($this->_attributes,$newattributes);
    }

    /**
    * Methos to return all the attributes of the element
    *
    */
    public function retrieveAllAttributes() {
        return $this->_attributes;
    }

    /**
    * Set or reset an attribute
    *
    * @param string $varname
    * @param string $value
    */
    public function __set($varname,$value) {
        $this->setAttribute($varname, $value);
    }
    /**
     * The __get method might be overwritten. It's better if the code to get the attribute is in another method so that it can be called directly
     *
     * @param string $varname
     */

    public function __get($varname) {
    	return $this->getAttribute($varname);
    }

    /**
     * Return element html
     *
     */
    public function html() {
        if (!$this->tpl_path)
            throw new acs_exception('No element view ' . get_class($this));

        $tpl = new acs_view($this->tpl_path,false);

        //$data = $this->beforeHtml(); //must be here to allow the before_html to alter the attributes
        $data = $this->beforeHtml(); //Must change call in other classes before I can change here

        if ($this->hasAttributes())
            $tpl->attributes = $this->getAttributes();

        return $tpl->addData($data)->returnRender();
    }

    /**
    * To be overloaded in the child class
    * This will be called by the method html()
    *
    * @return array()
    * 	Return an associative array
    *
    */
    protected function beforeHtml() {
        return array();
    }

    /**
    * @deprecated
    *
    */
    protected function before_html() {
        return $this->beforeHtml();
    }

    public function __toString() {
        return $this->html();
    }
}