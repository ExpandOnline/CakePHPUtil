<?php
namespace CakePHPUtil\Lib\TryProxy;
use Exception;

/**
 * Class TryProxy
 *
 * @package CakePHPUtil\Lib\TryProxy
 */
abstract class AbstractRetryHandler implements TryProxyHandler {

	/**
	 * @var
	 */
	private $amountOfRetries = 0;

	/**
	 * @param Exception $e
	 * @param           $callable
	 *
	 * @return mixed
	 * @throws Exception
	 * @throws ReportDownloadException
	 */
	public function handle(Exception $e, $callable) {
		if ($this->amountOfRetries >= 10) {
			throw $this->_getException();
		}
		$this->_sleep();
		$this->amountOfRetries += 1;

		return $callable();
	}

	/**
	 *
	 */
	public function reset() {
		$this->amountOfRetries = 0;
	}

	/**
	 *
	 */
	protected function _sleep() {
		sleep(1 + (pow($this->amountOfRetries, 2)) + 10000 / 1000);
	}

	/**
	 * @return Exception
	 */
	protected abstract function _getException();


}