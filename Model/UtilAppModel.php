<?php
App::uses('Model', 'Model');
App::uses('EntityObject', 'CakePHPUtil.Lib/Entity');

/**
 * Class UtilAppModel
 */
class UtilAppModel extends Model {

/**
 * See Model::find for the full docs.
 *
 * @param string $type
 * @param array  $query
 *
 * @return array|null
 */
	public function find($type = 'first', $query = array()) {
		$results = parent::find($type, $query);
		if (isset($query['objects']) && $query['objects'] === true) {
			$this->_toEntities($type, $query, $results);
		}
		return $results;
	}

/**
 * Turns the result array of a find into EntityObject instances.
 *
 * @param string $type		Find type, see find phpdoc.
 * @param array$options		The options array passed to a find.
 * @param array $results 	The results of the normal find.
 *
 */
	protected function _toEntities($type, $options, &$results) {
		if ($type === 'count') {
			return;
		}
		if ($type === 'first') {
			if (!empty($results)) {
				$results = $this->_createEntityObject($results, $options);
			}
			return;
		}
		foreach ($results as &$result) {
			$result = $this->_createEntityObject($result, $options);
		}
	}

/**
 * @param array $item The item to get the entity class name for.
 *                    This is unused here, but can be used in overwritten implementations.
 *
 * @return string The entity class name.
 */
	protected function _getEntityClassName($item) {
		return $this->alias . 'Entity';
	}

/**
 * @param $item
 * @param $options
 *
 * @return EntityObject
 */
	protected function _createEntityObject($item, $options) {
		$entityClassName = $this->_getEntityClassName($item);
		$pluginPrefix = $this->_getPluginPrefix();
		App::uses($entityClassName, $pluginPrefix . 'Lib/Entity/' . $this->alias);
		if (!class_exists($entityClassName)) {
			throw new InternalErrorException('Class ' . $entityClassName . ' could not be found!');
		}
		$options['contain'] = isset($options['contain']) ? $options['contain'] : array();
		if (!$entityClassName::approveContain($options['contain'])) {
			throw new InternalErrorException('Contain did not meet Entity Class requirements');
		}
		return new $entityClassName($item);
	}

/**
 * @return string
 */
	protected function _getPluginPrefix() {
		if (!empty($this->plugin)) {
			return $this->plugin . '.';
		}
		return '';
	}
}