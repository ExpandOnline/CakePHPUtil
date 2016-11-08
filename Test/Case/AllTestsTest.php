<?php
/**
 * Class AllTestsTest
 *
 * Custom test suite to execute all tests
 */
class AllTestsTest extends PHPUnit_Framework_TestSuite {

	/**
	 * @return CakeTestSuite
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$dirs = array(
			'Controller',
			'Model',
			'Lib',
			array('Model', 'Behavior'),
			'View',
			array('View', 'Helper'),
			'Shell'
		);

		$path = realpath(dirname(__FILE__)) . DS;
		self::addTestDirectories($suite, $path, $dirs);
		return $suite;
	}

	/**
	 * @param CakeTestSuite $suite
	 * @param               $path
	 * @param array         $dirs
	 */
	public static function addTestDirectories(CakeTestSuite $suite, $path, array $dirs) {
		foreach ($dirs as $dir) {
			$suite->addTestDirectory($path . (is_array($dir) ? implode(DS, $dir) : $dir) . DS);
		}
	}

}