<?php
App::uses('BaseApiScope', 'CakePHPUtil.Lib/Api/Scopes');

/**
 * Class LogApiScope
 */
class ExampleApiScope extends BaseApiScope {
	/**
	 * @return string
	 */
	public function getName() {
		return 'example';
	}
}