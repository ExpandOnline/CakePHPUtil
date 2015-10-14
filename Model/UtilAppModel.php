<?php
App::uses('Model', 'Model');
App::uses('EntityObject', 'CakePHPUtil.Lib/Entity');

/**
 * Class UtilAppModel
 */
class UtilAppModel extends Model {

/**
 * Queries the DataSource and returns a result set array.
 *
 * Used to perform find operations, where the first argument is type of find operation to perform
 * (all / first / count / neighbors / list / threaded),
 * second parameter options for finding (indexed array, including: 'conditions', 'limit',
 * 'recursive', 'page', 'fields', 'offset', 'order', 'callbacks')
 *
 * Eg:
 * ```
 * $model->find('all', array(
 *   'conditions' => array('name' => 'Thomas Anderson'),
 *   'fields' => array('name', 'email'),
 *   'order' => 'field3 DESC',
 *   'recursive' => 2,
 *   'group' => 'type',
 *   'callbacks' => false,
 * 	 'objects' => true
 * ));
 * ```
 *
 * In addition to the standard query keys above, you can provide DataSource, and behavior specific
 * keys. For example, when using a SQL based datasource you can use the joins key to specify additional
 * joins that should be part of the query.
 *
 * ```
 * $model->find('all', array(
 *   'conditions' => array('name' => 'Thomas Anderson'),
 *   'joins' => array(
 *     array(
 *       'alias' => 'Thought',
 *       'table' => 'thoughts',
 *       'type' => 'LEFT',
 *       'conditions' => '`Thought`.`person_id` = `Person`.`id`'
 *     )
 *   )
 * ));
 * ```
 *
 * ### Disabling callbacks
 *
 * The `callbacks` key allows you to disable or specify the callbacks that should be run. To
 * disable beforeFind & afterFind callbacks set `'callbacks' => false` in your options. You can
 * also set the callbacks option to 'before' or 'after' to enable only the specified callback.
 *
 * ### Adding new find types
 *
 * Behaviors and find types can also define custom finder keys which are passed into find().
 * See the documentation for custom find types
 * (http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#creating-custom-find-types)
 * for how to implement custom find types.
 *
 * Specifying 'fields' for notation 'list':
 *
 * - If no fields are specified, then 'id' is used for key and 'model->displayField' is used for value.
 * - If a single field is specified, 'id' is used for key and specified field is used for value.
 * - If three fields are specified, they are used (in order) for key, value and group.
 * - Otherwise, first and second fields are used for key and value.
 *
 * Note: find(list) + database views have issues with MySQL 5.0. Try upgrading to MySQL 5.1 if you
 * have issues with database views.
 *
 * Note: find(count) has its own return values.
 *
 * @param string $type Type of find operation (all / first / count / neighbors / list / threaded)
 * @param array $query Option fields (conditions / fields / joins / limit / offset / order / page / group / callbacks)
 * @return EntityObject|array|null Array of records, or Null on failure.
 * @link http://book.cakephp.org/2.0/en/models/retrieving-your-data.html
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