<?php
/**
 * Created by PhpStorm.
 * User: switteveen
 * Date: 27-11-2015
 * Time: 17:54
 */

class FileStoreFile extends SplFileObject {

	protected $_fileStoreId = null;

	/**
	 * @param null $fileStoreId
	 */
	public function setFileStoreId($fileStoreId) {
		if (!is_null($this->_fileStoreId)) {
			throw new MethodNotAllowedException('The ID of this file is already set!');
		}
		$this->_fileStoreId = $fileStoreId;
	}

	/**
	 * @return null
	 */
	public function getFileStoreId() {
		return $this->_fileStoreId;
	}


	public function getPath() {
		throw new MethodNotAllowedException('This file and it\'s location are managed by the FileStore');
	}

	public function getPathname() {
		throw new MethodNotAllowedException('This file and it\'s location are managed by the FileStore');
	}

	public function getRealPath() {
		throw new MethodNotAllowedException('This file and it\'s location are managed by the FileStore');
	}

	public function getPathInfo($class_name = null) {
		throw new MethodNotAllowedException('This file and it\'s location are managed by the FileStore');
	}
}