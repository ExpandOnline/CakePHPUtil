<?php

class DateData extends Data {

/**
 * @var DateTime $_date
 */
	protected $_date = null;

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
		return $this->_date->format('d-m-Y H:i:s');
	}

/**
 * @return string
 */
	public function __toString() {
		return $this->getDate();
	}
}
