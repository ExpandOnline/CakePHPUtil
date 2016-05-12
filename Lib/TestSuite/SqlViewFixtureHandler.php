<?php
App::uses('SqlViewTest', 'CakePHPUtil.Lib/TestSuite');
App::uses('SqlViewFixture', 'CakePHPUtil.Lib/TestSuite');

/**
 * Class SqlViewFixtureHandler
 */
class SqlViewFixtureHandler {

/**
 * @var array
 */
	protected static $dropStatements = [];

/**
 * @var array
 */
	protected static $viewFixtures = [];

/**
 * @var array
 */
	protected static $tableFixtures = [];

/**
 * @param SqlViewTest $testCase
 * Initialization for a SqlViewTest. Must be called before the parent constructor of the SqlViewTest,
 * because the fixture array for regular fixtures may be modified.
 */
	public static function init(SqlViewTest $testCase) {
		static::$viewFixtures = [];
		static::$tableFixtures = [];
		static::$dropStatements = [];

		foreach ($testCase->getSqlViewFixtures() as $fixture) {
			static::_appendFixtureRecursive($fixture);
		}
		static::$viewFixtures = array_reverse(static::$viewFixtures);
		$testCase->fixtures = array_unique(array_merge(static::$tableFixtures, $testCase->fixtures));
	}

/**
 * Will create the actual view(s). Must be called after init, but before fixtures are used.
 * Best place would probably be the setUp() method of the SqlViewTest.
 */
	public static function createViews() {
		foreach (array_unique(static::$viewFixtures) as $fixture) {
			static::loadSqlViewFixture($fixture);
		}
	}

/**
 * Drops the views. Must be called after testing is completed. Best place would probably be the tearDown() method
 * of SqlViewTest.
 */
	public static function dropViews() {
		$db = ConnectionManager::getDataSource('test');
		foreach(static::$dropStatements as $statement) {
			$db->execute($statement);
		}
	}

	protected static function _appendFixtureRecursive($fixture) {
		static::$viewFixtures[] = $fixture;
		$fixture = self::getFixtureInstance($fixture);
		static::$tableFixtures = array_merge(static::$tableFixtures, $fixture->getTableFixtureDependencies());
		foreach($fixture->getViewFixtureDependencies() as $fixture) {
			static::_appendFixtureRecursive($fixture);
		}
	}

/**
* @param SqlViewTest $testCase
*/
	protected static function loadSqlViewFixture ($fixture) {
		$fixture = self::getFixtureInstance($fixture);
		$dataSource = $fixture->getDataSourceName();
		$testDb = ConnectionManager::getDataSource($dataSource == 'default' ? 'test' : 'test_' . $dataSource);
		$sourceDb = ConnectionManager::getDataSource($dataSource);
		$view = $fixture->getViewName();
		$statement = static::getCreateStatement($sourceDb, $view);
		$testDb->execute($statement);
		self::$dropStatements[] = sprintf('DROP VIEW %s;', $view);
	}

/**
 * @param DataSource $sourceDb
 * @param            $viewName
 *
 * @return mixed
 */
	protected static function getCreateStatement(DataSource $sourceDb, $viewName) {
		$resultSet = $sourceDb->execute(sprintf('SHOW CREATE VIEW %s', $viewName));
		foreach($resultSet as $result) {
			if (array_key_exists('Create View', $result) && !empty($result['Create View'])) {
				preg_match('/(?:CREATE(?:\s+OR\s+REPLACE)?\s+)'
					. '(ALGORITHM=[^\s]+\s+)?(?:DEFINER=`[^`]+`@`[^`]+`\s+)?'
					. '(?:SQL\s+SECURITY\s+(?:DEFINER|INVOKER)\s+)?VIEW\s+`([^`]+)`\s+AS\s+(.*)$/',
					$result['Create View'],
					$matches
				);
				return sprintf('CREATE OR REPLACE %sVIEW `%s` AS %s' , $matches[1], $matches[2], $matches[3]);
			}
		}

		throw new InternalErrorException(sprintf(
			'Could not fetch the create statement for view %s in schema %s. Check your MySQL View fixture settings!',
			$viewName,
			$sourceDb->getSchemaName()
		));
	}

/**
 * @param $fixture
 *
 * @return SqlViewFixture
 */
	protected static function getFixtureInstance($fixture) {
		if (strpos($fixture, '.') === false) {
			throw new InternalErrorException(sprintf('malformed fixture: %s does not contain \'.\'!', $fixture));
		}
		$parts = explode('.', $fixture);
		$fixtureName = array_pop($parts);
		$fixtureName .= 'Fixture';
		/**
		 * @var SqlViewFixture $fixture
		 */
		$fixture = new $fixtureName();

		return $fixture;
	}

}