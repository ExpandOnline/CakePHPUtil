<?php

/**
 * Class PercentageData
 */
class PercentageData extends Data {

/*
 * @var float|int
 */
	protected $_percentage = null;

/**
 * @param float|int $percentage
 */
	public function __construct($percentage) {
		$this->_percentage = $percentage;
	}

/**
 * @return string
 */
	public function getType() {
		return Data::PERCENTAGE;
	}

/**
 * @return float|int|null
 */
	public function getPercentage() {
		return $this->_percentage;
	}

/**
 * @return string
 */
	public function __toString() {
		return round($this->_percentage, 2) . '%';
	}
}
