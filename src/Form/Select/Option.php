<?php

namespace Form\Select;

class Option extends element {

    private $_text = null;

	public function __construct($value,$text, $selected = false) {
		$this->tpl_path = 'html/forms/form_select_option';

        $this->value($value);
        $this->text($text);

        if ($selected)
            $this->selected(true);
	}

    public function selected($bool = true) {
        return $this->setAttribute('selected',($bool ? 'selected' : null));
    }

    public function value($value) {
        return $this->setAttribute('value',$value);
    }

    public function text($text) {
        $this->_text = $text;
        return $this;
    }

    public function getValue() {
        return $this->getAttribute('value');
    }

    public function beforeHtml() {
        return array('text' => $this->_text);
    }
}