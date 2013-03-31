<?php

/**
 *  Check -> http://www.w3schools.com/TAGS/tag_input.asp
 *   for all attributes of input
 *
 * Input only - http://www.tizag.com/htmlT/htmlinput.php
 *
 * Text - http://www.tizag.com/htmlT/htmltextfields.php
 * Password - http://www.tizag.com/htmlT/htmlpassword.php
 * Checkboxes - http://www.tizag.com/htmlT/htmlcheckboxes.php
 * Radio - http://www.tizag.com/htmlT/htmlradio.php
 * File - http://www.tizag.com/htmlT/htmlupload.php
 * Buttons
 *           Button - http://particletree.com/features/rediscovering-the-button-element/ (this talks about using the button tag instead of the input tag for normal buttons)
 *           Submit - http://www.tizag.com/htmlT/htmlsubmit.php
 *           Reset - http://www.tizag.com/htmlT/htmlreset.php
 *
 * Hidden - http://www.tizag.com/htmlT/htmlhidden.php
 *
 *
 *
 */
namespace Form;

class Input extends element {

    protected $_fileFolderPath = null;

	public function __construct($name, $label = null) {

	    //By default the type is set to text
		$this->setAttribute('type','text');
		$this->setAttribute('name',$name);
		$this->setAttribute('id',$name . '_id');

		$this->tpl_path = 'html/forms/form_input';

        if ($label)
            $this->setLabel($label);
	}

	//Wrappers for readonly, disabled and checkep attributes
	public function readonly($state = true) {
		return $this->setAttribute('readonly',$state ? 'readonly' : null);
	}
	public function disabled($state = true) {
		return $this->setAttribute('disabled',$state ? 'disabled' : null);
	}
	public function checked($state = true) {
		return $this->setAttribute('checked',$state ? 'checked' : null);
	}

    public function setMinLength($min) {
        return $this->setAttribute('_minlength',$min);
    }

    public function setMaxLength($max) {
        return $this->setAttribute('_maxlength',$max);
    }

    /**
    * File type method. Set the folder where to put the file
    *
    * @param string $folder
    */
    public function setFolder($folder) {
        return $this->_fileFolderPath = $folder;
        return $this;
    }

    public function getFolder() {
        return $this->_fileFolderPath;
    }

    /**
    * Valid extensions for files
    *
    * @param string $ext - Extensions seperated by comma. Ex.: jpg,gif,...etc
    */
    public function setValidExtensions($ext) {
        return $this->setAttribute('_ext',$ext);
    }
}
