<?php
App::import('Vendor', 'ModelBehavior', array(
	'file' => 'cakephp' . DS . 'cakephp' . DS . 'lib' . DS . 'Cake' . DS . 'Model' . DS . 'ModelBehavior.php'
));

/**
 * Class MongoBehavior
 */
class MongoBehavior extends ModelBehavior {

/**
 * Drop the current table.
 *
 * @throws InternalErrorException
 */
	public function dropTable($model) {
		if (!$model->useTable) {
			return;
		}
		$mongo = $model->getDataSource()->getMongoDb();
		$table = (is_object($mongo) && $this->_tableExists($model))
			? $model->getDataSource()->getMongoDb()->{$model->useTable}
			: null;
		if (is_null($table)) {
			//Note that this exception prevents a Fatal Error on the line below.
			throw new InternalErrorException(sprintf('Tried to delete Table %s, but it does not exist', $model->useTable));
		}
		$model->getDataSource()->getMongoDb()->{$model->useTable}->drop();
	}

/**
 * Check if the current table exists.
 *
 * @return bool
 */
	protected function _tableExists($model) {
		return $model->getDataSource()->getMongoDb()->system->namespaces->findOne(array(
			'name' => $model->getDataSource()->config['database'] . '.' . $model->useTable
		)) != null;
	}

/**
 * Group by method.
 *
 * @param array $field
 *
 * @return array
 */
	public function groupBy($model, $field) {
		$fieldArray = explode('/', $field);

		$conditions = array(
			'aggregate' => array()
		);
		$this->_buildUnwindConditions($conditions['aggregate'], count($fieldArray));
		$conditions['aggregate'][] = array(
			'$match' => array(
				'content.name' => array_pop($fieldArray)
			)
		);
		$conditions['aggregate'][] = array(
			'$group' => array(
				'_id' => array(
					'value' => '$content.content'
				),
				'count' => array(
					'$sum' => 1
				),
			)
		);
		$conditions['aggregate'][] = array(
			'$sort' => array('count' => -1)
		);
		$conditions['aggregate'][] = array(
			'$limit' => $model::LIMIT
		);

		return $model->find('all', array(
			'conditions' => $conditions,
		));
	}

/**
 * Build '$unwind' conditions.
 * After the 1st depth we have to unwind the 'content' array.
 * Unwinding means handling the object in the arrays as separate rows.
 * After the 2nd depth the 'content' key can be a string or array.
 *
 * @param array $aggregate The aggregate conditions array.
 * @param int $depth
 *
 * @return array
 */
	protected function _buildUnwindConditions(&$aggregate, $depth) {
		foreach (range(0, $depth) as $currentDepth) {
			if ($currentDepth > 1) {
				$contentPrefix = $this->_getContentPrefixByDepth($currentDepth, false);
				$aggregate[] = array(
					'$match' => array(
						$contentPrefix => array(
							// 3 is an object in MongoDB.
							'$type' => 3
						)
					)
				);
			}
			if ($currentDepth > 0) {
				$contentPrefix = $this->_getContentPrefixByDepth($currentDepth, false);
				$aggregate[] = array(
					'$unwind' => '$' . $contentPrefix
				);
			}
		}
	}

/**
 * Get the content prefix.
 *
 * @param int $depth
 * @param bool $trailingDot
 *
 * @return string
 */
	protected function _getContentPrefixByDepth($depth, $trailingDot = true) {
		if ($depth == 0) {
			return '';
		}
		return implode(".", array_fill(0, $depth, "content")) . ($trailingDot ? '.' : '');
	}

/**
 * Get an array of all collections in the mongo source.
 * @param $model
 *
 * @return mixed
 */
	public function listSources($model) {
		$mongoCursor = $model->getDataSource()->getMongoDb();
		return $mongoCursor->listCollections();
	}

/**
 * Insert multiple documents at once.
 * @param Model $model
 * @param array $data
 */
	public function batchInsert(Model $model, array $data) {
		$model->getDataSource()->getMongoCollection($model)->batchInsert($data);
	}

/**
 * Returns a mongo cursor for the current collection.
 * @param Model $model
 *
 * @return mixed
 */
	public function getMongoCursor(Model $model) {
		return $model->getDataSource()->getMongoCollection($model)->find();
	}

	public function getMongoAggregateCursor(Model $model, $command = []) {
		return $model->getDataSource()->getMongoCollection($model)->aggregateCursor($command);
	}

/**
 * @param Model $model
 */
	public function dropCurrentCollection(Model $model){
		 $model->getDataSource()->getMongoCollection($model)->drop();
	}

	/**
	 * @param Model $model
	 * @param       $validationRules
	 *
	 * @return bool
	 */
	public function validatesMongo(Model $model, $validationRules) {
		$mongoData = $model->data[$model->alias];
		$validates = true;
		$model->validationErrors = [];
		foreach ($validationRules as $requiredFieldPath => $fieldLabel) {
			$value = Hash::get($mongoData, $requiredFieldPath);
			if (false === $value || empty($value)) {
				$model->validationErrors[] = $fieldLabel . ' mag niet leeg gelaten worden.';
				$validates = false;
			}
		}
		
		return $validates;
	}

	/**
	 * @param Model $model
	 * @param       $lockedOffsets
	 *
	 * @return array|null
	 */
	public function getRandomItems(Model $model, $lockedOffsets) {
		/**
		 * @var int $total
		 */
		$total = $model->find('count');
		$randomOffsets = array_diff($this->_getRandomOffsets($total), $lockedOffsets);
		if (count($lockedOffsets) > $total) {
			array_splice($lockedOffsets, $total);
		}
		foreach ($lockedOffsets as &$lockedOffset) {
			if (is_null($lockedOffset)) {
				$lockedOffset = array_pop($randomOffsets);
			}
		}
		return $this->_getMongoItemsByOffsets($model, $lockedOffsets);
	}

	/**
	 * @param Model $model
	 * @param       $offsets
	 *
	 * @return array
	 */
	protected function _getMongoItemsByOffsets(Model $model, $offsets) {
		$items = array();
		foreach ($offsets as $offset) {
			$item = $model->find('all', array(
				'limit' => 1,
				'offset' => $offset
			));
			$item[0][$model->alias]['lock_id'] = $offset;
			$items[] = $item[0][$model->alias];
		}
		return $items;
	}

	/**
	 * @param int $total
	 * @param int $limit
	 *
	 * @return array
	 */
	protected function _getRandomOffsets($total, $limit = 5) {
		if ($total == 0) {
			return array();
		}
		$randomPool = range(0, $total - 1);
		if ($limit > $total) {
			$limit = $total;
		}
		return array_rand($randomPool, $limit);
	}
}