<?php
App::uses('FileSystemFileStore', 'CakePHPUtil.Lib/FileStore');
class FileSystemFileStoreTest extends CakeTestCase {

	public function testFileSystemFileStore() {
		$storage = new testFileStore();
		$path1 = TMP . 'test' . DS . 'storage_test_1.txt';
		$path2 = TMP . 'test' . DS . 'storage_test_2.txt';
		if ($storage->exists(1)) {
			$storage->delete(1);
		}
		$file = new SplFileObject($path1, 'w+');
		$file->fwrite('test 1');
		$this->assertFalse($storage->exists(1));
		$storage->storeFile(1, $file, false);
		$exception = null;
		try {
			$storage->storeFile(1,$file,  false);
		} catch (Exception $e) {
			$exception = $e;
		}
		$this->assertEquals('File already exists. Use force to overwrite it.', $exception->getMessage());
		$this->assertTrue($storage->exists(1));

		unset($file);
		$file = $storage->get(1);
		$file->next();
		$this->assertEquals('test 1', $file->getCurrentLine());
		unset($file);
		$file2 = new SplFileObject($path2, 'w+');
		$file2->fwrite('test 2');
		$storage->storeFile(1, $file2, true);
		unset($file2);
		$file = $storage->get(1);
		$file->next();
		$this->assertEquals('test 2', $file->getCurrentLine());
		unset($file);
		$this->assertTrue($storage->delete(1));
		$exception = false;
		try {
			$storage->delete(1);
		} catch (Exception $e) {
			$exception = $e;
		}
		$this->assertEquals('File for ID 1 could not be found!', $exception->getMessage());
		$this->assertFalse($storage->exists(1));
		unset($storage);
		unlink($path1);
		unlink($path2);
	}
}

class testFileStore extends FileSystemFileStore {

/**
 * Returns the complete file path given an id.
 *
 * @param $id
 *
 * @return mixed
 */
	protected function _getPath($id) {
		return TMP . 'test' . DS . 'test_storage_' . $id . '.txt';
	}

}