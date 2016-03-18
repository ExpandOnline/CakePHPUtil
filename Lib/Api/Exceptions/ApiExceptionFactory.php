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
	 * @param $message
	 *
	 * @return JsonApiException
	 */
	public static function jsonApiException($message){
		return new JsonApiException($message);
	}
}