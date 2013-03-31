<?php

namespace Form\Select;

class Optgroup extends element_container {


	public function __construct($label, $options) {
		$this->tpl_path = 'html/forms/form_select_optgroup';

        $this->label($label);

        foreach ($options as $text => $value) {
            $this->addOption($text,$value);
        }
	}

    public function label($label) {
        return $this->setAttribute('label',$label);
    }

    public function addOption($value,$text) {
        $this->add(new form_select_option($value,$text));
    }

    public function select($value) {
        if (!empty($this->_elements)) {
            foreach ($this->_elements as $k => $element) {
                if ($element->getValue() == $value) {
                    $this->_elements[$k]->selected();
                    return true;
                }
            }
        }
        return false;
    }
}