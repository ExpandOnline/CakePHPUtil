<?php

abstract class Data {

	const DATE = 'Date';
	const LINK = 'Link';
	const COLLECTION = 'Collection';
	const ACTION = 'Action';
	const SHORT = 'Short';
	const HOURS = 'Hours';
	const ERROR = 'Error';
	const ICON = 'Icon';
	const FINANCIAL = 'Financial';

/**
 * @return string
 */
	public abstract function getType();

/**
 * @return string
 * @throws Exception
 */
	public function __toString() {
		throw new Exception('Tostring not implemented.');
	}
}