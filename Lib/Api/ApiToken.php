<?php
App::uses('BaseApiScope', 'CakePHPUtil.Lib/Api/Scopes');

use Firebase\JWT\JWT;

/**
 * Class ApiToken
 */
class ApiToken {

	const FIELD_ID = 'sub';

	/**
	 * @var string
	 */
	protected $_token = '';

	/**
	 * @var array
	 */
	protected $_fields = [];

	/**
	 * ApiToken constructor.
	 *
	 * @param null  $id
	 * @param array $scopes
	 * @param array $extraFields
	 */
	public function __construct($id = null, $scopes = [], array $extraFields = []) {

		if ($id === null) {
			return;
		}

		$this->setId($id);

		if (!empty($scopes) && $scopes[0] instanceof BaseApiScope) {
			$this->setScopes($scopes);
		} else {
			$this->setRawScopes($scopes);
		}

		$this->setExtraFields($extraFields);
	}


	/**
	 * @param $secretKey
	 *
	 * @return string
	 */
	public function encode($secretKey) {

		$fields = array_merge($this->_fields,
			[
				// https://tools.ietf.org/html/rfc7519#section-4.1.6
				'iat' => time()
			]);

		$jwtToken = JWT::encode($fields, $secretKey);


		return $this->_token = $jwtToken;
	}

	/**
	 * @param       $jwtToken
	 * @param       $secretKey
	 * @param array $method
	 *
	 * @return $this
	 */
	public function decode($jwtToken, $secretKey, $method = ['HS256']) {
		$this->_fields = (array)JWT::decode($jwtToken, $secretKey, $method);

		return $this;
	}


	/**
	 * @param $id
	 */
	public function setId($id) {
		$this->_setValue([static::FIELD_ID => $id]);
	}

	/**
	 * @param array $scopes
	 */
	public function setScopes(array $scopes) {
		$this->_setValue(['scopes' => (new ApiScopeValidator())->resolveScopes($scopes)]);
	}

	/**
	 * @param array $scopes
	 */
	public function setRawScopes(array $scopes) {
		$this->_setValue(['scopes' => $scopes]);
	}

	/**
	 * @param array $extraFields
	 */
	public function setExtraFields(array $extraFields) {
		$tempArr = $this->_fields;

		$this->_fields = $extraFields;
		$this->setId($tempArr[static::FIELD_ID]);
		$this->setRawScopes($tempArr['scopes']);

	}

	/**
	 * @return mixed
	 */
	public function getId() {
		if (array_key_exists(static::FIELD_ID, $this->_fields)) {
			return $this->_fields[static::FIELD_ID];
		}

		return null;
	}

	/**
	 * @return mixed
	 */
	public function getRawScopes() {
		if (array_key_exists('scopes', $this->_fields)) {
			return $this->_fields['scopes'];
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getExtraFields() {
		return array_diff_key($this->_fields, [
			static::FIELD_ID,
			'scopes'
		]);
	}

	/**
	 * @param array $value
	 */
	protected function _setValue(array $value) {
		$this->_fields = array_merge(
			$this->_fields,
			$value
		);
	}

}