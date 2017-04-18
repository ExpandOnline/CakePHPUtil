<?php

App::uses('Data', 'CakePHPUtil.Lib/Data');
class DateData extends Data {

/**
 * @var DateTime $_date
 */
	protected $_date = null;

/**
 * @var string
 */
	protected $_format = 'd-m-Y H:i:s';

/**
 * @param string $format
 */
	public function setFormat($format) {
		$this->_format = $format;
	}

/**
 * @param $dateString
 */
	public function __construct($dateString) {
		$this->_date = new DateTime($dateString, new DateTimeZone('CEST'));
	}

/**
 * @return string
 */
	public function getType() {
		return Data::DATE;
	}

/**
 * @return string
 */
	public function getDate() {
		return $this->_date->format($this->_format);
	}

/**
 * @return string
 */
	public function __toString() {
		return $this->getDate();
	}

/**
 * @return DateTime
 */
	public function getDateTime() {
		return new DateTime($this->getDate());
	}
}
