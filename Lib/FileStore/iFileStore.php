<?php

interface iFileStore {
/**
 * @param               $id
 * @param SplFileObject $file
 * @param Bool          $force
 */
	public function storeFile($id, SplFileObject $file, $force = false);

/**
 * @param $id
 *
 * @return bool
 */
	public function isValidId($id);

/**
 * @param $id
 *
 * @return bool
 */
	public function exists($id);

/**
 * @param $id
 *
 * @return SplFileObject
 */
	public function get($id);

/**
 * @param $id
 *
 * @return bool
 * @throws Exception if id is not a valid ID for an existing file.
 */
	public function delete($id);
}