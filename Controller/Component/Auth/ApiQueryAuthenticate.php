<?php
App::uses('ApiAuthenticate', 'CakePHPUtil.Controller/Component/Auth');

/**
 * Class ApiQueryAuthenticate
 * Adds the possibility to authenticate using an authorization querystring rather than header.
 * Although this is less safe than having the token in the header (as it will not be encrypted in the querystring)
 * it provides great usability for less tech savvy users.
 */

class ApiQueryAuthenticate extends ApiAuthenticate {

	private $_authQueryParameter = 'authorization';

	private $_isQueryToken = true;

	/**
	 * @param CakeRequest $request
	 *
	 * @return mixed
	 */
	protected function _getAuthString(CakeRequest $request) {
		if ($request->query($this->_authQueryParameter)) {
			$token = $request->query($this->_authQueryParameter);
			unset($request->query[$this->_authQueryParameter]);
			return $token;
		}
		$this->_isQueryToken = false;
		return parent::_getAuthString($request);
	}

	public function getUser(CakeRequest $request) {
		$user = parent::getUser($request);
		$this->_checkExpiration($user);
		return $user;
	}

	/**
	 * @param $user
	 *
	 * @throws InvalidApiAuthorizationException
	 */
	protected function _checkExpiration($user):void {
		if ($this->_isQueryToken && (empty($user['token']->getExtraFields()['expires'])
				|| $user['token']->getExtraFields()['expires'] < time())
		) {
			throw ApiExceptionFactory::invalidAuthorizationException(
				"Token has expired: Generate a new token."
			);
		}
	}


}