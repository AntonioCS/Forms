<?php

/**
* For info on the textarea element
*  - http://www.tizag.com/htmlT/htmltextarea.php
*/

namespace Form;

class Label extends element {

    private $_text = null;

    public function __construct($name, $text = null) {
        $this->setAttribute('for',$name);
        if ($text)
            $this->text($text);

        $this->tpl_path = 'html/forms/form_label';
    }

    public function text($text) {
        $this->_text = $text;
    }

    public function beforeHtml() {
        return array('text' => $this->_text);
    }
}