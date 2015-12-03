<?php

interface iFileStore {
/**
 * @param               $id
 * @param SplFileObject $file
 * @param Bool          $force
 */
	public function storeFile($id, SplFileObject $file, $force = false);

/**
 * @param               $id
 * @param String 		$path
 * @param Bool          $force
 */
	public function storeFileByPath($id, $path, $force = false);

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

/**
 * Outputs a file to the client.
 *
 * Make sure to set the appropriate headers before calling this method!
 *
 * @param $id
 *
 */
	public function output($id);

/**
 * @param $id
 *
 * @return mixed
 * @throws InvalidArgumentException if $id is not valid.
 * @throws NotFoundException if $id is valid, but the file does not exist.
 */
	public function getValidatedPath($id);

}