<?php

App::uses('JsonApiException', 'CakePHPUtil.Lib/Api/Exceptions');
App::uses('InvalidApiAuthorizationException', 'CakePHPUtil.Lib/Api/Exceptions');

/**
 * Class ApiExceptionFactory
 */
class ApiExceptionFactory {

	/**
	 * @param $message
	 *
	 * @return InvalidApiAuthorizationException
	 */
	public static function invalidAuthorizationException($message){
		return new InvalidApiAuthorizationException($message);
	}

	/**
	 * @param      $message
	 *
	 * @param null $previousException
	 *
	 * @return JsonApiException
	 */
	public static function jsonApiException($message, $previousException = null){
		return new JsonApiException($message, 0, $previousException);
	}
}