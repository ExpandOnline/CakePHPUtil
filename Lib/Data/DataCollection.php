<?php

class DataCollection extends Data {

/**
 * @var Data[] $_dataCollection
 */
	protected $_dataCollection = array();

/**
 * @param array $dataCollection
 */
	public function __construct(array $dataCollection) {
		$this->_dataCollection = $dataCollection;
	}

/**
 * @return string
 */
	public function getType() {
		return Data::COLLECTION;
	}

/**
 * @return Data[]
 */
	public function getCollection() {
		return $this->_dataCollection;
	}
}
