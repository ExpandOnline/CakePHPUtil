<?php
App::uses('Data', 'CakePHPUtil.Lib/Data/');

class HoursData extends Data {

/**
 * @var string $_text
 */
	protected $_text = null;

/**
 * @param string $text
 */
	public function __construct($text = '') {
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
	 * @param $seconds
	 *
	 * @return $this
	 */
	public function setSeconds($seconds) {
		$this->_text = $this->_secondsToTime($seconds);

		return $this;
	}

	/**
	 * @param      $seconds
	 *
	 * @return mixed
	 */
	protected function _secondsToTime($seconds) {
		$seconds = round($seconds / (60)) * (60);

		return floor($seconds / 3600) . ':' . sprintf('%02d', ($seconds % 3600) / 60);
	}
}
