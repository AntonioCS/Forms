<?php

/**
* For info on the textarea element
*  - http://www.tizag.com/htmlT/htmltextarea.php
*/

namespace Form;

class Textarea extends element {

	private $_cols = 45;
	private $_rows = 5;

	private $_text = null;

	public function __construct($name) {
		$this->setAttribute('name',$name);
		$this->setAttribute('cols',$this->_cols);
		$this->setAttribute('rows',$this->_rows);

		$this->tpl_path = 'html/forms/form_textarea';
	}

	public function text($text) {
		$this->_text = $text;
	}

	public function beforeHtml() {
		return array('text' => $this->_text);
	}

}