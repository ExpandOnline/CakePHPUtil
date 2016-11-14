<?php

/**
 * Class Data
 */
abstract class Data {

	const DATE = 'Date';
	const LINK = 'Link';
	const COLLECTION = 'Collection';
	const ACTION = 'Action';
	const SHORT = 'Short';
	const FIXED_SHORT = 'FixedShort';
	const HOURS = 'Hours';
	const ERROR = 'Error';
	const ICON = 'Icon';
	const FINANCIAL = 'Financial';
	const PERCENTAGE = 'Percentage';
	const HTML = 'Html';
	const HTML_DIFF = 'HtmlDiff';


	const POSITIVE = 1;

	const NEUTRAL = 2;

	const NEGATIVE = 3;

	const WARNING = 4;

/**
 * @var null indicates the nature of this data (e.g. positive for good, negative for bad, etc.)
 */
	protected $_sign = null;

/**
 * @return string
 */
	public abstract function getType();

/**
 * @return string
 * @throws Exception
 */
	public function __toString() {
		throw new Exception('__toString not implemented.');
	}


/**
 * @param $sign
 */
	public function setSign($sign) {
		$this->_sign = $sign;
	}

/**
 * @return int
 */
	public function getSign() {
		return isset($this->_sign) ? $this->_sign : self::NEUTRAL;
	}
}