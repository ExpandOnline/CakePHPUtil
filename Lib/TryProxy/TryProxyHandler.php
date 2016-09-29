<?php
namespace CakePHPUtil\Lib\TryProxy;

/**
 * Interface TryProxyHandler
 *
 * @package CakePHPUtil\Lib\TryProxy
 */
interface TryProxyHandler {

	/**
	 * @param \Exception $e
	 * @param            $callable
	 *
	 * @return mixed
	 */
	public function handle(\Exception $e, $callable);

	/**
	 * @return mixed
	 */
	public function reset();

	/**
	 * Returns whether or not this handler will handle the exception
	 *
	 * @param \Exception $exception
	 *
	 * @return bool
	 */
	public function willHandle(\Exception $exception);

}