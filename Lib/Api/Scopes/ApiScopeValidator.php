<?php

/**
 * Class ApiScopeValidator
 */
class ApiScopeValidator {

	/**
	 * Check if a user has a list of scopes
	 *
	 * @param array $givenScopes    An array that contains ['log.read', 'log.create', etc]
	 * @param array $requiredScopes An array of BaseApiScopes
	 *
	 * @return bool
	 */
	public static function hasScopes($givenScopes, array $requiredScopes) {
		if (!is_array($givenScopes) || empty($givenScopes)) {
			return false;
		}

		if (!is_array($requiredScopes)) {
			return false;
		}

		$resolvedRequiredScopes = (new self())->resolveScopes($requiredScopes);

		$diff = array_diff($resolvedRequiredScopes, $givenScopes);

		// If givenScopes doesn't have all required scopes, $diff will contain missing scopes
		return empty($diff);

	}

	/**
	 * @param $scopeObjArr
	 *
	 * @return array
	 */
	public function resolveScopes($scopeObjArr) {
		$resolvedScopes = [];

		// Merge each scope object into an array with scope.strings
		// Result will be something like ["logs.read", "logs.write", "contacts.read"]
		foreach ($scopeObjArr as $scopeObj) {
			/* @var $requiredScope BaseApiScope */
			$resolvedScopes = array_merge($resolvedScopes, $scopeObj->toArray());
		}

		return $resolvedScopes;
	}
}