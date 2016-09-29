<?php
namespace CakePHPUtil\Lib\TryProxy;

/**
 * Class TryProxy
 *
 * @package CakePHPUtil\Lib\TryProxy
 */
class TryProxy {

	/**
	 * Object
	 */
	private $target;

	/**
	 * TryProxyHandler
	 */
	private $handler;

	/**
	 * TryProxy constructor.
	 *
	 * @param                       $target
	 * @param TryProxyHandler		$handler
	 */
	public function __construct($target, TryProxyHandler $handler)
	{
		$this->target = $target;
		$this->handler = $handler;
	}

	/**
	 * The "weird" function is because we don't have curried callables
	 * (callables with arguments applied, thus a function that is partially applied)
	 * which we do need in this proxy.
	 *
	 * @param $method
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __call($method, $arguments)
	{
		try {
			$response = call_user_func_array([$this->target, $method], $arguments);
			$this->handler->reset();
			return $response;
		} catch (\Exception $e) {
			if (!$this->handler->willHandle($e)) {
				throw $e;
			}
			return $this->handler->handle(
				$e,
				function() use ($method, $arguments) {
					return call_user_func_array([$this, $method], $arguments);
				}
			);

		}
	}

}