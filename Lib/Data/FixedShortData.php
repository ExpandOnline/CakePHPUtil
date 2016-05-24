<?php
App::uses('ShortData', 'CakePHPUtil.Lib/Data');

class FixedShortData extends ShortData {

	/**
	 * @var
	 */
	protected $_class;

	public function __construct($text, $class = 'fixed-short') {
		parent::__construct($text);
		$this->_class = $class;
	}
	/**
	 * @return string
	 */
	public function getType() {
		return Data::FIXED_SHORT;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->_class;
	}
	
}
