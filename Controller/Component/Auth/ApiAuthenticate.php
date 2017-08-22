<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('ApiExceptionFactory', 'CakePHPUtil.Lib/Api/Exceptions');

App::uses('ApiToken', 'CakePHPUtil.Lib/Api');	

/**
 * Class ApiAuthenticate
 */
class ApiAuthenticate extends BaseAuthenticate {

	/**
	 * @var string
	 */
	protected $_authHeaderName = 'Authorization';

	/**
	 * @var string
	 */
	protected $_secretKey = null;

	/**
	 * @var bool
	 */
	public $sessionKey = false;

	/**
	 * @param CakeRequest  $request
	 * @param CakeResponse $response
	 *
	 * @return array
	 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		return $this->getUser($request);
	}

	/**
	 * @param CakeRequest $request
	 *
	 * @return array
	 * @throws InvalidApiAuthorizationException
	 * @throws JsonApiException
	 */
	public function getUser(CakeRequest $request) {
		$this->_secretKey = Configure::read('API.SECRET_KEY');
		if (empty($this->_secretKey)) {
			throw ApiExceptionFactory::jsonApiException('Internal server error, contact ' . DEV_EMAIL);
		}
		try {
			$jwt = (new ApiToken())->decode($this->_getAuthString($request), $this->_secretKey);
		} catch (Exception $e) {
			throw ApiExceptionFactory::invalidAuthorizationException("Invalid token: could not decrypt token.");
		}

		if(!$jwt->getId()){
			throw ApiExceptionFactory::invalidAuthorizationException(
				"Invalid token: missing 'sub' field in payload."
			);
		}

		return ['token' => $jwt];

	}

	/**
	 * @param CakeRequest  $request
	 * @param CakeResponse $response
	 *
	 * @return bool
	 * @throws InvalidApiAuthorizationException
	 */
	public function unauthenticated(CakeRequest $request, CakeResponse $response) {
		throw ApiExceptionFactory::invalidAuthorizationException('Unauthorized');
	}

	/**
	 * @param CakeRequest $request
	 * @return mixed
	 * @throws InvalidApiAuthorizationException
	 */
	protected function _getAuthString(CakeRequest $request) {
		$header = CakeRequest::header($this->_authHeaderName);
		if(!$header) {
			throw ApiExceptionFactory::invalidAuthorizationException('Missing Authorization header.');
		}

		if (false === stripos($header, 'Bearer ')) {
			throw ApiExceptionFactory::invalidAuthorizationException('Non-supported Authorization header provided.');
		}

		return str_ireplace('Bearer ', '', $header);
	}
}