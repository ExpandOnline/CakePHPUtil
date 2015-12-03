<?php
/**
 * For reasons of safety and predictability, most of the methods in this class are declared final.
 */
App::uses('iFileStore', 'CakePHPUtil.Lib/FileStore');

abstract class FileSystemFileStore implements iFileStore {


/**
 * Returns the complete file path given an id.
 * @param $id
 *
 * @return mixed
 */
	protected abstract function _getPath($id);

/**
 * @param               $id
 * @param SplFileObject $file
 * @param bool          $force
 *
 * @return bool
 */
	public final function storeFile($id, SplFileObject $file, $force = false) {
		return $this->storeFileByPath($id, $file->getRealPath(), $force);
	}

/**
 * @param        $id
 * @param String $path
 * @param bool   $force
 *
 * @return bool
 */
	public final function storeFileByPath($id, $path, $force = false) {
		$this->_validateId($id);
		$storepath = $this->_getPath($id);
		if (!$force && $this->_pathExists($storepath)) {
			throw new MethodNotAllowedException('File already exists. Use force to overwrite it.');
		}
		return copy($path, $storepath);
	}

/**
 * @param $id
 *
 * @return bool
 */
	public function isValidId($id) {
		return is_numeric($id) && $id > 0 && $id < 999999999999;
	}

/**
 * @param $id
 *
 * @return bool
 */
	public final function exists($id) {
		$this->_validateId($id);
		return $this->_pathExists($this->_getPath($id));
	}

/**
 * @param $id
 *
 * @return SplFileObject
 */
	public final function get($id) {
		$path = $this->getValidatedPath($id);
		return new SplFileObject($path);
	}

/**
 * @param $id
 *
 * @return bool
 * @throws Exception if id is not a valid ID for an existing file.
 */
	public final function delete($id) {
		$path = $this->getValidatedPath($id);
		return unlink($path);
	}

/**
 * The ID's should be carefully validated to avoid confusion between ID's and filenames.
 * @return mixed
 * @throws InvalidArgumentException if ID is not valid.
 */
	protected function _validateId($id) {
		if (!$this->isValidId($id)) {
			throw new InvalidArgumentException(sprintf($id, '%s is not a valid ID'));
		}
	}

/**
 * @param $path
 *
 * @return bool
 */
	protected function _pathExists($path) {
		return file_exists($path);
	}

/**
 * @param $id
 *
 * @return mixed
 * @throws InvalidArgumentException if $id is not valid.
 * @throws NotFoundException if $id is valid, but the file does not exist.
 */
	public function getValidatedPath($id) {
		$this->_validateId($id);
		$path = $this->_getPath($id);
		if (!$this->_pathExists($path)) {
			throw new NotFoundException(sprintf('File for ID %s could not be found!', $id));
		}
		return $path;
	}

/**
 * Outputs a file to the client.
 *
 * Make sure to set the appropriate headers before calling this method!
 *
 * @param $id
 *
 */
	public function output($id) {
		$path = $this->getValidatedPath($id);
		readfile($path);
	}

}