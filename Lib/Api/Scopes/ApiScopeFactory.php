<?php

App::uses('LogApiScope', 'CakePHPUtil.Lib/Api/Scopes');

/**
 * Class ApiScopeFactory
 */
class ApiScopeFactory {

	/**
	 * @return LogApiScope
	 */
	public static function logScope() {
		return new LogApiScope();
	}
}