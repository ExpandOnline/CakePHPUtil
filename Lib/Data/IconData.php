<?php

class IconData extends Data {

/*
 * @var string $_text
 */
	protected $_icon = null;

/*
 * @var array $_options
 */
	protected $_options = array();

	public function __construct($icon, $options) {
		$this->_icon = $icon;
		$this->_options = $options;
	}

/**
 * @return string
 */
	public function getType() {
		return Data::ICON;
	}

/**
 * @return null
 */
	public function getIcon() {
		return $this->_icon;
	}

/**
 * @return array
 */
	public function getOptions() {
		return $this->_options;
	}
}
