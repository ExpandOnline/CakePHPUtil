<?php

class ActionData extends DataCollection {

/**
 * @var LinkData[] $actionArray
 */
	public $actionArray = array();

/**
 * @var array
 */
	public $options = array();

	/**
	 * @var bool
	 */
	protected $_isDropUp = false;

/**
 * @param array $actionArray
 * @param array $options
 *
 * @throws Exception
 */
	public function __construct(array $actionArray, $options = array()) {
		$this->options = $options;
		foreach ($actionArray as $action) {
			if (!$action instanceof LinkData) {
				throw new Exception("ActionData collection may only contain LinkData.");
			}
			$this->actionArray[] = $action;
		}
	}

	/**
	 * @return boolean
	 */
	public function isDropUp() {
		return $this->_isDropUp;
	}

	/**
	 * @param boolean $isDropUp
	 */
	public function setDropUp($isDropUp) {
		$this->_isDropUp = $isDropUp;
	}



/**
 * @return string
 */
	public function getType() {
		return Data::ACTION;
	}

/**
 * @return array
 */
	public function getActionAsArray() {
		$response = array();
		foreach ($this->actionArray as $action) {
			$response[$action->getAnchor()] = $action->getUrl();
		}
		return $response;
	}
}
