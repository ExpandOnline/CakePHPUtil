<?php

class HtmlData extends Data {

/**
 * @var String
 */
	protected $_html = null;

/**
 * @return string
 */
	public function getType() {
		return Data::HTML;
	}

	function __construct($html) {
		$this->_html = $html;
	}

	function __toString() {
		return $this->_html;
	}

}