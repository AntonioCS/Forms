<?php


class fieldset extends element_container {

    private $_legend = null;

    public function __construct($id = null, $legend = null) {

        if ($id)
            $this->setId($id);
        if ($legend)
            $this->legend($legend);

		$this->setProcessWrap();

        $this->tpl_path = 'html/fieldset';
    }

    public function beforeHtml() {

        $legend = null;

        if ($this->_legend)
            $legend = $this->_legend->html();

		return parent::beforeHtml(array('legend' => $legend), $this->getAttribute('id'));

		/*
        return  array_merge(
                    $this->processElementsWrap($this->getAttribute('id')),
                    array('legend' => $legend)
                );        */
    }

    /**
    * Set the legend class
    *
    * @return legend
    */
    public function legend($legend = null) {
        if (!$this->_legend)
            $this->_legend = new legend($legend);

        return $this->_legend;
    }
}

class legend extends element {

    private $_title = null;

    public function __construct($legend = null) {
        if ($legend)
            $this->title($legend);

        $this->tpl_path = 'html/legend';
    }

    public function title($text) {
        $this->_title = $text;
    }

    public function before_html() {
        return array('title' => $this->_title);
    }
}