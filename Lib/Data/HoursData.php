<?php

app::uses('Data', 'CakePHPUtil.Lib/Data/');

class HoursData extends Data {

	const POSITIVE = 1;

	const NEUTRAL = 2;

	const NEGATIVE = 3;

/**
 * @var null indicates the nature of this number (e.g. positive for good, negative for bad, etc.)
 */
	protected $_sign = null;

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

/**
 * @param $sign
 */
	public function setSign($sign) {
		if ($sign == self::POSITIVE || $sign == self::NEGATIVE || $sign == self::NEUTRAL) {
			$this->_sign = $sign;
		}
	}

/**
 * @return int
 */
	public function getSign() {
		return isset($this->_sign) ? $this->_sign : self::NEUTRAL;
	}
}
