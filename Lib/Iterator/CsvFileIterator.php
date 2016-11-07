<?php
namespace CakePHPUtil\Lib\Iterator;
use SplFileObject;

/**
 * Class CsvFileIterator
 */
class CsvFileIterator extends \IteratorIterator {

	/**
	 * @var array
	 */
	protected $_headers = [];

	/**
	 * @inheritDoc
	 */
	public function __construct($path, $separator = ',') {
		$file = new SplFileObject($path);
		$file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
		$file->setCsvControl($separator);
		parent::__construct($file);
	}

	/**
	 * @inheritDoc
	 */
	public function current() {
		if (empty($this->_headers)) {
			$this->_headers = parent::current();
			parent::next();
		}
		return array_combine($this->_headers, parent::current());
	}

}