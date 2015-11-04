<?php

app::uses('Data', 'CakePHPUtil.Lib/Data/');

class HoursData extends Data {

/**
 * @var string $_text
 */
	protected $_text = null;

/**
 * @param string $text
 */
	public function __construct($text) {
		$this->_text = $text;
	}

/**
 * @return string
 */
	public function getType() {
		return Data::HOURS;
	}

/**
 * @return string
 */
	public function __toString() {
		return $this->_text;
	}
}
