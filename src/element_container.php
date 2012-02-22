<?php

/**
* For elements which are containers (div, spam, fieldset, etc)
*/
abstract class element_container extends element {

	/**
	 * If the element should process normally or should wrap the elements
	 *
	 * @var int - 0 normal process, 1 wrap process
	 */
	protected $_process_type = 0;

    /**
    * Wrapper of the elements
    *
    * @var mixed
    */
    protected $_wrapper = 'div';

    /**
    * This will hold the elements
    *
    * @var array
    */
    protected $_elements = array();

    /**
    * This will hold a reference to the position of the element in the elements array (_elements),
    * by usin
    *   id_element => ref
    *
    * @var array
    */
    protected $_elements_ref = array();

    /**
    * To automatically add a label based on the name/id of the element
    *
    * @var bool
    */
    private $_autoaddlabel = false;


    /**
    * To add html directly
    *
    * @param string $element
    *
    * @deprecated Just use the add() method
    */
    public function addHtml($html) {
        $this->add($html);
    }

    /**
    * This will be used to add controls to the form
    * This will have more parameters depending on the control
    *
    * @param string $element_type
    * @param string $element_ref
    * @param string $element_label
    * @return mixed
    */
    public function addElement($element_type,$element_ref,$element_label = null) {
        $newelement = null;
        $nolabel = false;

        switch ($element_type) {
            case 'input':
                $newelement = new form_input($element_ref);
            break;
            case 'select':
                $newelement = new form_select($element_ref);
            break;
            case 'textarea':
                $newelement = new form_textarea($element_ref);
            break;
            case 'fieldset':
                $newelement = new fieldset($element_ref,$element_label);
                $nolabel = true;
            break;

            default:
                throw new exception('Unknown element');
        }
        //Automatically set an id. The user can remove this by doing a setId()
        $newelement->setId($element_ref);

        if (!$nolabel) {
            $label = null;

            if ($element_label)
                $label = $element_label;
                //$newelement->label($element_label);
            elseif ($this->_autoaddlabel)
                $label = str_replace('_',' ',ucfirst($element_ref));

            if ($label)
                $newelement->setLabel($label);
                //$newelement->label(str_replace('_',' ',ucfirst($element_name)));
        }

        return $this->add($newelement);
    }

    //Wrappers for addElement
    //--- Input
    //public function addButton($element_name = null,$element_label = null) { -- Doesn't make sense to me to have a label for the button element
    /**
    * Wrapper to create an input element with type button
    *
    * @param string $element_name
    *
    * @return input
    */
    public function addButton($element_name) {
        return $this->addElement('input',$element_name)->setType('button');
    }

    /**
    *
    * @param mixed $element_name
    * @param mixed $value
    *
    * @return input
    */
    public function addSubmit($element_name, $value = null) {
        return $this->addElement('input',$element_name)->setType('submit')->setValue($value != null ? $value : 'Submit');
    }

    /**
    *
    * @param mixed $element_name
    * @param mixed $value
    *
    * @return input
    */
    public function addReset($element_name, $value = null) {
        return $this->addElement('input',$element_name)->setType('reset')->setValue($value != null ? $value : 'Reset');
    }

    /**
    *
    * @param mixed $element_name
    * @param mixed $element_label
    *
    * @return input
    */
    public function addPassword($element_name,$element_label = null) {
        return $this->addElement('input',$element_name, $element_label)->setType('password');
    }

    /**
    *
    *
    * @param mixed $element_ref
    * @param mixed $element_name
    * @param mixed $element_value
    * @param mixed $element_label
    *
    * @return input
    */
    public function addCheckbox($element_ref,$element_name,$element_value = null,$element_label = null) {
        return $this->addElement('input',$element_ref,$element_label)
                        ->setAttribute('name',$element_name)
						->setType('checkbox')
						->setAttribute('value',$element_value);
    }

    //TODO: There can be many radios with one name (so that they all are in a group) http://www.echoecho.com/htmlforms10.htm
    /**
    *
    * @param string $element_name
    * @param string $element_label
    *
    * @return input
    */
    public function addRadio($group, $element_ref, $element_value = null, $element_label = null) {
        return $this->addElement('input',$element_ref,$element_label)
                        ->setAttribute('name', $group)
						->setType('radio')
						->setAttribute('value',$element_value);
    }

    /**
    * put your comment there...
    *
    * @param string $element_name
    * @param string $element_label
    */
    public function addText($element_name,$element_label = null) {
        return $this->addElement('input',$element_name,$element_label)->setType('text');
    }

    /**
    * put your comment there...
    *
    * @param mixed $element_name
    * @param mixed $value
    */
    public function addHidden($element_name, $value = null) {
        return $this->addElement('input',$element_name)->setType('hidden')->setValue($value);
    }

    /**
    *  Create a group
    *
    * @param string $container_name
    * @param string $label
    *
    * @return element_container
    */
    /**
    * Create grouped radios or check boxes
    *
    * @param string $groupname
    * @param string $type
    * @param array $values
    * @param string $label
    * @return mixed
    */
	public function addGroup($groupname, $type, array $values, $label = null) {
		$container = new div($groupname . '_div');

		$container->setProcessWrap();

		if ($label)
			$container->add(new form_label(null, $label));

        $counter = 0;
        if ($type == 'check') {
            foreach ($values as $name => $value) {
                $container->addCheckbox($groupname . $counter++,$groupname . '[]', $name, $value , $value);
            }
        }
        elseif ($type == 'radio') {
            foreach ($values as $name => $value) {
                $container->addRadio($groupname,$groupname . $counter++,$value,$name);
            }
        }

        return $this->add($container);
	}

    public function addGroupCheckbox() {

    }

    /**
    * To set the state of the _autoaddlabel property
    *
    * @param bool $state
    */
    public function autoaddlabel($state = true) {
        $this->_autoaddlabel = $state;
        return $this;
    }

    //--- Input End

    /**
    * Wrapprer to create a select element
    *
    * @param string $element_name
    * @param string $element_label
    * @param array $data
    *
    * @return form_select
    */
    public function addSelect($element_name = null,$element_label = null, $data = null) {
		/**
		 * @var form_select
		 */
        $sel = $this->addElement('select',$element_name,$element_label);

		if ($data)
			$sel->createList($data);

		return $sel;
    }
    /**
    * Wrapprer to create a textarea element
    *
    * @param mixed $element_name
    * @param mixed $element_label
    *
    * @return form_textarea
    */
    public function addTextArea($element_name,$element_label = null) {
        return $this->addElement('textarea',$element_name,$element_label);
    }

    /**
    * Create a fieldset element
    *
    * @param mixed $element_name
    * @param mixed $legend
    * @return element_container
    */
    public function addFieldSet($element_name, $legend = null) {
        return $this->addElement('fieldset',$element_name, $legend);
    }

    /**
    * Add element to the container
    *
    * @param mixed $element Can either be pure html, element or element_container
    */
    public function add($element, $positonToAdd = null) {

        $ref = null;
        if (is_object($element)) { //assume element descendent
            //check to see if has an id or name and use it for reference in the elements
            $ref = $element->getAttribute('id') ?: $element->getAttribute('name'); //PHP 5.3

            $element->setParent($this);
        }

        //DONE: Create an array for the references, so that the _elements array will be a numeric array and not an associative
        if ($positonToAdd !== null) {
            helper_array::inject($this->_elements,$positonToAdd,$element);
        }
        else
            $this->_elements[] = $element;

        if ($ref) {
            if ($positonToAdd !== null)
                $this->_elements_ref[$ref] = $positonToAdd;
            else
                $this->_elements_ref[$ref] = array_pop(array_keys($this->_elements));
        }

        //Since this is an object and it's done by reference I can just return the reference to the object or code in case of it no being an object
        return $element;
    }

    /**
    * Wrapper for the add() method so that it can return the instance of the class and not the element that was added (useful in certain cases)
    *
    * @param mixed $element
    */
    public function addThis($element) {
        if ($this->add($element))
            return $this;

        return null;
    }

    /**
    * Add element before 'element'
    *
    * @param string $element_ref
    * @param mixed $element
    *
    * @return mixed
    */
    public function addBefore($element_ref, $element) {
        if (isset($this->_elements_ref[$element_ref])) {
            //Since I am using the helper_array::inject I don't have to decrease the value of _elements_ref. The inject method will push the other element down
            return $this->add($element,$this->_elements_ref[$element_ref]);
        }

        return null;
    }

    /**
    * Add after element
    *
    * @param mixed $element_ref
    * @param mixed $element
    * @return mixed
    */
    public function addAfter($element_ref, $element) {
        if (isset($this->_elements_ref[$element_ref])) {
            $pos = $this->_elements_ref[$element_ref] +1;
            return $this->add($element,$pos);
        }

        return null;
    }

    /**
    * Add element to the top position
    *
    * @param mixed $element
    * @return mixed
    */
    public function addTop($element) {
        return $this->add($element,0);
    }

    public function __get($name) {
        return $this->getElement($name) ?: parent::__get($name); //PHP 5.3
    }

    /**
    * Return reference of element
    *
    * @param string $ref
    */
    public function getElement($ref) {
        if (isset($this->_elements_ref[$ref])) {
            return $this->_elements[$this->_elements_ref[$ref]];
        }
        return null;
    }

	/**
	 * Se process type
	 *
	 * @param string $type - (For now) NoWrap or Wrap
	 *
	 * @return element_container
	 */
	public function setProcess($type = 'NoWrap') {
		switch ($type) {
			case 'NoWrap':
				$this->_process_type = 0;
			break;
			case 'Wrap':
				$this->_process_type = 1;
			break;
		}

		return $this;
	}

	public function setProcessNoWrap() {
		$this->setProcess('NoWrap');
	}

	public function setProcessWrap() {
		$this->setProcess('Wrap');
	}


    /**
    * Process the elements
    *
    * @param array $data
    * @return array
    */
    public function beforeHtml($data = array(), $container_name = null) {
        return array_merge(
            array(
                'elements' => implode(
								"\n",
								($this->_process_type == 0 ?
									$this->processElementsNoWrap() :
									$this->processElementsWrap($container_name)
								)
							)
            ),
            $data
        );
    }

	/**
	 *	Process elements with no wrap
	 * @return type
	 */
    protected function processElementsNoWrap() {
        $elements = array();

        if (!empty($this->_elements)) {
            foreach ($this->_elements as $element) {
                $elements[] = (is_object($element) ? $element->html() : $element);
            }
        }

        return $elements;
    }

    /**
    * Process elements with a wrapper
    *
    * @param string $container_name Name for the element wrapper
    */
    protected function processElementsWrap($container_name) {

        if ($this->_wrapper == 'div')
            $wrapper = new wrapper_div($container_name);

        $total_elements = array();

        if (!empty($this->_elements)) {
            $hidden = array();

            foreach ($this->_elements as $element) {
                if (!is_object($element))
                    $wrapper->noWrap($element);
                else {
                    switch ($element->type) {
                        case 'hidden':
                            if ($element->getAttribute('name') == 'MAX_FILE_SIZE')
                                $wrapper->noWrap($element);
                            else
                                $hidden[] = $element->html();
                        break;
                        case 'submit':
                        case 'reset':
                           $wrapper->wrapSubmit($element);
                        break;
                        default:
                            //if ($element);
                            $wrapper->wrap($element);
                    }
                }
            }

            $total_elements[] = $wrapper->output() . implode("\n",$hidden);
			//array('elements' => $wrapper->output() . implode("\n",$hidden));
        }

        return $total_elements;
    }


}
