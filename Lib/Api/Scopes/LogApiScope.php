<?php
App::uses('BaseApiScope', 'CakePHPUtil.Lib/Api/Scopes');

/**
 * Class LogApiScope
 */
class LogApiScope extends BaseApiScope {
	/**
	 * @return string
	 */
	public function getName() {
		return 'logs';
	}
}