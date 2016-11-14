<?php

class HtmlDiffData extends Data {

	/**
	 * @var string
	 */
	protected $_valueOne;

	/**
	 * @var string
	 */
	protected $_valueTwo;


	/**
	 * @return string
	 */
	public function getType() {
		return Data::HTML_DIFF;
	}

	function __construct($valueOne, $valueTwo) {
		$this->_valueOne = $valueOne;
		$this->_valueTwo = $valueTwo;
	}

	function __toString() {
		return '<span class="word-difference" data-value1="'
		. htmlentities($this->_valueOne) . '" data-value2="'
		. htmlentities($this->_valueTwo) . '"></span>';
	}

}