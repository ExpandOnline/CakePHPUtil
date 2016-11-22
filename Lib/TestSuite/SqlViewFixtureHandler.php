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
	protected $dropStatements = [];

/**
 * @var array
 */
	protected $viewFixtures = [];

/**
 * @var array
 */
	protected $tableFixtures = [];

/**
 * @param SqlViewTest $testCase
 * Initialization for a SqlViewTest. Must be called before the parent constructor of the SqlViewTest,
 * because the fixture array for regular fixtures may be modified.
 */
	public function init(SqlViewTest $testCase) {
		$this->viewFixtures = [];
		$this->tableFixtures = [];
		$this->dropStatements = [];

		foreach ($testCase->getSqlViewFixtures() as $fixture) {
			$this->_appendFixtureRecursive($fixture);
		}
		$this->viewFixtures = array_reverse($this->viewFixtures);
		$testCase->fixtures = array_unique(array_merge($this->tableFixtures, $testCase->fixtures));
	}

/**
 * Will create the actual view(s). Must be called after init, but before fixtures are used.
 * Best place would probably be the setUp() method of the SqlViewTest.
 */
	public function createViews() {
		foreach (array_unique($this->viewFixtures) as $fixture) {
			$this->loadSqlViewFixture($fixture);
		}
	}

/**
 * Drops the views. Must be called after testing is completed. Best place would probably be the tearDown() method
 * of SqlViewTest.
 */
	public function dropViews() {
		$db = ConnectionManager::getDataSource('test');
		foreach($this->dropStatements as $statement) {
			$db->execute($statement);
		}
	}

	protected function _appendFixtureRecursive($fixture) {
		$this->viewFixtures[] = $fixture;
		$fixture = $this->getFixtureInstance($fixture);
		$this->tableFixtures = array_merge($this->tableFixtures, $fixture->getTableFixtureDependencies());
		foreach($fixture->getViewFixtureDependencies() as $fixture) {
			$this->_appendFixtureRecursive($fixture);
		}
	}

/**
* @param SqlViewTest $testCase
*/
	protected function loadSqlViewFixture ($fixture) {
		$fixture = $this->getFixtureInstance($fixture);
		$dataSource = $fixture->getDataSourceName();
		$testDb = ConnectionManager::getDataSource($dataSource == 'default' ? 'test' : 'test_' . $dataSource);
		$sourceDb = ConnectionManager::getDataSource($dataSource);
		$testDb->cacheSources = false;
		$view = $fixture->getViewName();
		$statement = $this->getCreateStatement($sourceDb, $view);
		$testDb->execute($statement);
		$this->dropStatements[] = sprintf('DROP VIEW %s;', $view);
	}

/**
 * @param DataSource $sourceDb
 * @param            $viewName
 *
 * @return mixed
 */
	protected function getCreateStatement(DataSource $sourceDb, $viewName) {
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
	protected function getFixtureInstance($fixture) {
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