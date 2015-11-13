<?php
App::uses('Data', 'CakePHPUtil.Lib/Data');
App::uses('DateData', 'CakePHPUtil.Lib/Data');
App::uses('LinkData', 'CakePHPUtil.Lib/Data');
App::uses('DataCollection', 'CakePHPUtil.Lib/Data');
App::uses('ActionData', 'CakePHPUtil.Lib/Data');
App::uses('ShortData', 'CakePHPUtil.Lib/Data');
App::uses('HoursData', 'CakePHPUtil.Lib/Data');
App::uses('ErrorData', 'CakePHPUtil.Lib/Data');
App::uses('IconData', 'CakePHPUtil.Lib/Data');
App::uses('FinancialData', 'CakePHPUtil.Lib/Data');
App::uses('PercentageData', 'CakePHPUtil.Lib/Data');

/**
 * Class EntityObject
 */
abstract class EntityObject extends ArrayObject {

/**
 * Returns the text that should be used as main display line for this object (e.g. Account Name)
 * @return mixed
 */
	public abstract function displayName();

/**
 * The URL that this URL's main display should link to. (e.g. Account/edit/#id URL)
 *
 * @return mixed
 */
	public abstract function displayUrl();

/**
 * Checks whether this object has a displayUrl.
 * @return bool
 */
	public function hasUrl() {
		return $this->displayUrl() !== false;
	}

/**
 * @return LinkData
 */
	public function link() {
		return new LinkData($this->displayUrl(), $this->displayName());
	}

/**
 * Checks whether this object is a low priority object.
 *
 * Low priority generally refers to something like inactive, old, non-billable, etc.
 *
 * @return bool
 */
	public function isLowPriority() {
		return false;
	}

/**
 * this method is called by the find right before casting the find results to entities. This method is much more efficient
 * than the checkArrayKeys method above, because it is only called once per find, rather than once per entity. It should
 * therefore eventually replace the _checkAraryKeys entirely.
 *
 * @param array $contain
 *
 * @return bool
 */
	public static function approveContain($contain) {
		return true;
	}

/**
 * @param $amount
 *
 * @return mixed
 */
	protected function _toMicro($amount) {
		return $amount * 1000000;
	}
}