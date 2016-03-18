<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('ApiExceptionFactory', 'CakePHPUtil.Lib/Api/Exceptions');
App::uses('ApiScopeFactory', 'CakePHPUtil.Lib/Api/Scopes');

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
	protected $_token_key = null;
	/**
	 * @var array
	 */
	protected $_encrypt_method = ['HS256'];

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

		// Since this component is autoloaded by Cake, no clue how to inject secret key.
		$this->_token_key = Configure::read('API.SECRET_KEY');
		if (empty($this->_token_key)) {
			throw ApiExceptionFactory::jsonApiException('Development error');
		}

		$authHeader = $this->_getAuthHeader();

		try {
			$jwt = (new ApiToken())->decode($authHeader, $this->_token_key);

			if(!$jwt->getId()){
				throw ApiExceptionFactory::invalidAuthorizationException('Invalid token');
			}

			return ['token' => $jwt];

		} catch (Exception $e) {
			throw ApiExceptionFactory::invalidAuthorizationException('Invalid token');
		}
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
	 * @return mixed
	 * @throws InvalidApiAuthorizationException
	 */
	protected function _getAuthHeader() {
		$headers = getallheaders();

		if (!isset($headers[$this->_authHeaderName]) || null === $headers[$this->_authHeaderName]) {
			throw ApiExceptionFactory::invalidAuthorizationException('Authorization token required');
		}

		if (false === stripos($headers[$this->_authHeaderName], 'Bearer ')) {
			throw ApiExceptionFactory::invalidAuthorizationException('Invalid Authorization formatting');
		}

		return str_ireplace('Bearer ', '', $headers[$this->_authHeaderName]);
	}
}