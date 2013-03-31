<?php

/**
* For info on the select element
*  - http://www.w3schools.com/TAGS/tag_select.asp
*
*  optgroup - http://www.w3schools.com/TAGS/tag_optgroup.asp
*  option - http://www.w3schools.com/TAGS/tag_option.asp
*/

namespace Form;

class Select extends element_container {

    /**
    *
    * @param string $name
    * @param array $data - Associative array of ('text' => 'value') or 'teste => array('bla' => 'ble')

    * @return form_select
    */
	public function __construct($name, $data = null) {
		$this->tpl_path = 'html/forms/form_select';

        $this->setAttribute('name',$name);

        if ($data)
            $this->createList ($data);
	}

	/**
	 * Create select items from data
	 *
	 * @param type $data
	 */
	public function createList($data) {
		foreach ($data as $k => $value) {
			if (is_array($value))
				$this->addOptGroup($k,$value);
			else
				$this->addOption($k,$value);
		}
	}

	/**
	 * Add one option
	 *
	 * @param string $value
	 * @param string $text
	 */
	public function addOption($value, $text) {
		$this->add(new form_select_option($value,$text));
	}

	/**
	 * Add an array of options
	 *
	 * @param array $valueText
	 */
    public function addOptions($valueText) {

        foreach ($valueText as $value => $text) {
            $this->addOption($value,$text);
        }
    }

    /**
    * Add group to select
    *
    * @param string $label
    * @param array $data
    *
    * @return form_select_optgroup
    */
    public function addOptGroup($label, $data) {
        return $this->add(new form_select_optgroup($label,$data));
    }

    /**
    * To select an option with the given value
    *
    * @param string $value
    */
    public function select($value) {
        if (!empty($this->_elements)) {
            foreach ($this->_elements as $k => $element) {
                if ($element instanceof form_select_optgroup) {
                    if ($element->select($value)) //DONE: Do code to transverse the options in the optgroup and see if it has the one to be selected
                        break;
                }
                else {
                    if ($element->getValue() == $value) {
                        $this->_elements[$k]->selected();
                        break;
                    }
                }
            }
        }
    }
}