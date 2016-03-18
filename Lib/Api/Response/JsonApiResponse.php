<?php

/**
 * Class JsonApiResponse
 */
class JsonApiResponse {

	/**
	 * Returns a JSON string with a mixed $data
	 * See specification: http://jsonapi.org/format/#document-top-level
	 *
	 * @param mixed $data
	 *
	 * @return string
	 */
	public static function data($data) {
		return json_encode([
			'data' => $data
		]);
	}
}