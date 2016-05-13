<?php
App::uses('SqlViewTest', 'CakePHPUtil.Lib/TestSuite');
App::uses('SqlViewFixtureHandler', 'CakePHPUtil.Lib/TestSuite');

/**
 * Class SqlViewTestCase
 */

abstract class SqlViewTestCase extends CakeTestCase implements SqlViewTest {

/**
 * @var array
 */
	public $sqlViewFixtures = [];

	/**
	 * @var SqlViewFixtureHandler
	 */
	protected $_sqlViewFixtureHandler;

/**
 * SqlViewTestCase constructor.
 *
 * @param null   $name
 * @param array  $data
 * @param string $dataName
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		$this->_sqlViewFixtureHandler = new SqlViewFixtureHandler();
		$this->_sqlViewFixtureHandler->init($this);
		parent::__construct($name, $data, $dataName);
	}

/**
 *
 */
	public function setUp() {
		parent::setUp();
		$this->_sqlViewFixtureHandler->createViews();
	}

/**
 *
 */
	public function tearDown() {
		$this->_sqlViewFixtureHandler->dropViews();
		parent::tearDown();
	}

}