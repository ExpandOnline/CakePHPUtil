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
 * SqlViewTestCase constructor.
 *
 * @param null   $name
 * @param array  $data
 * @param string $dataName
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		SqlViewFixtureHandler::init($this);
		parent::__construct($name, $data, $dataName);
	}

/**
 *
 */
	public function setUp() {
		parent::setUp();
		SqlViewFixtureHandler::createViews();
	}

/**
 *
 */
	public function tearDown() {
		SqlViewFixtureHandler::dropViews();
		parent::tearDown();
	}

}