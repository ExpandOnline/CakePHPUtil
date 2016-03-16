<?php
App::uses('Data', 'CakePHPUtil.Lib/Data/');

class HoursData extends Data {

/**
 * @var string $_text
 */
	protected $_text = null;

/**
 * @param            $text
 * @param bool|false $isSeconds
 */
	public function __construct($text = '', $isSeconds = false) {
		if ($isSeconds && !is_null($text)) {
			$text = floor($text / 3600) . ':' . floor(($text % 3600) / 60);
		}
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
		return is_null($this->_text) ? '' : $this->_text;
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
