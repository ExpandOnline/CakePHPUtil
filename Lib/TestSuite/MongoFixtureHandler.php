<?php
/**
 * Class MongoFixtureHandler
 *
 * Manages the insertion and deletion of mongoFixtures.
 * The fixture classes should have the following collections array:
 *
 * public $collections = array(
 * 	'firstCollectionName' => array(
 * 		array(
 * 			'FirstFieldOfFirstDocument' => 'value',
 * 			'SecondFieldOfFirstDocument' => 'value',
 * 		),
 * 		array(
 * 			'FirstFieldOfSecondDocument' => 'value',
 * 			'SecondFieldOfSecondDocument' => 'value',
 * 		),
 * 	),
 * 	'secondCollectionName' => array(...)
 * );
 *
 */


class MongoFixtureHandler {

	protected static $_models = array();

	/**
	 * Loads the fixtures from the mongoFixtures array in the testCase into the mongoDataSource.
	 * @param CakeTestCase $testCase
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function loadMongoFixtures (CakeTestCase $testCase) {
		if (!isset($testCase->mongoFixtures) || !is_array($testCase->mongoFixtures)) {
			return false;
		}
		foreach ($testCase->mongoFixtures as $fixtureName) {
			list($plugin, $fixtureName) = explode('.', $fixtureName);
			$fixture = new $fixtureName();
			$modelName = str_replace('Fixture', '', $fixtureName);
			$model = $testCase->getMockForModel($plugin . '.' . $modelName, array('test'));
			if (substr($model->useDbConfig, 0, 5) !== 'test_') {
				throw new InternalErrorException('The model for fixture '
					. $fixtureName . ' does not have a test datasource.');
			}
			self::$_models[] = $model;
			foreach($fixture->collections as $collection => $documents) {
				$model->setSource($collection);
				$model->batchInsert($documents);
			}
		}
		return true;
	}

/**
 * Deletes the collections for all models that were loaded using the loadMongoFixtures method.
 */
	public static function clearMongoDatabase() {
		foreach (self::$_models as $model) {
			if (substr($model->useDbConfig, 0, 5) !== 'test_') {
				throw new InternalErrorException('Tried to clear fixturedata for model '
					. $model->alias . ', but it is currently not using a test datasource.');
			}
			$collections = $model->listSources();
			/**
			 * @var MongoCollection $collection
			 */
			foreach ($collections as $collection) {
				$collection->drop();
			};
		}
		self::$_models = array();
	}

}