<?php
use CakePHPUtil\Lib\TryProxy\TryProxy;
use CakePHPUtil\Lib\TryProxy\TryProxyHandler;

/**
 * Class TryProxyTest
 */
class TryProxyTest extends CakeTestCase {

	/**
	 *
	 */
	public function testProxy() {
		$handler = new TestHandler();
		$cases = [
			[
				'throwException' => false,
			],
			[
				'throwException' => true,
			],
		];
		foreach ($cases as $case) {
			$handler->shouldReset = !$case['throwException'];
			$tryProxy = new TryProxy(new TestedClass($case['throwException']), $handler);
			$response = $tryProxy->call();
			$this->assertEquals($case['throwException'], $response);
		}
	}

}

/**
 * Class TestHandler
 */
class TestHandler extends CakeTestCase implements TryProxyHandler {

	/**
	 * @var bool
	 */
	public $shouldReset = false;

	/**
	 * @param \Exception $e
	 * @param            $callable
	 *
	 * @return mixed
	 */
	public function handle(\Exception $e, $callable) {
		$this->assertInstanceOf('Exception', $e);
		$this->assertEquals('Yep!', $e->getMessage());
		return true;
	}

	/**
	 * @return mixed
	 */
	public function reset() {
		$this->assertTrue($this->shouldReset);
	}

	/**
	 * Returns whether or not this handler will handle the exception
	 *
	 * @param \Exception $exception
	 *
	 * @return bool
	 */
	public function willHandle(\Exception $exception) {
		return true;
	}
}

/**
 * Class TestedClass
 */
class TestedClass {

	/**
	 * @var bool
	 */
	private $throwException;

	/**
	 * TestedClass constructor.
	 *
	 * @param $throwAnException
	 */
	public function __construct($throwAnException) {
		$this->throwException = $throwAnException;
	}

	/**
	 * @throws Exception
	 */
	public function call() {
		if ($this->throwException) {
			throw new Exception('Yep!');
		}
		return false;
	}

}