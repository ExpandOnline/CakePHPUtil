<?php

class ModelIterator implements Iterator, Countable {

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @var array
	 */
	protected $conditions;

	/**
	 * @var string
	 */
	protected $findType;

	/**
	 * @var int
	 */
	protected $limit = 500;

	/**
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * @var ArrayIterator
	 */
	protected $data;

	/**
	 * ModelIterator constructor.
	 *
	 * @param string $findType
	 * @param array $conditions
	 * @param Model $model
	 */
	public function __construct($findType, $conditions, $model) {
		$this->findType = $findType;
		$this->conditions = $conditions;
		$this->model = $model;
		$this->data = new ArrayIterator([]);
	}

	/**
	 * @return int
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @param int $limit
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
	}



	/**
	 * Return the current element
	 *
	 * @link  http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current() {
		return $this->data->current();
	}

	/**
	 * Move forward to next element
	 *
	 * @link  http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function next() {
		$this->data->next();
	}

	/**
	 * Return the key of the current element
	 *
	 * @link  http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key() {
		return $this->offset + $this->data->key();
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link  http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 *        Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid() {
		if (!$this->data->valid()) {
			$this->offset = $this->offset + $this->limit;
			$this->findData();
		}

		return $this->data->valid();
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link  http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind() {
		$this->offset = 0;
		$this->findData();
		$this->data->rewind();
	}

	/**
	 * Count elements of an object
	 *
	 * @link  http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 *        </p>
	 *        <p>
	 *        The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return $this->model->find('count', [
			'conditions' => $this->conditions
		]);
	}

	protected function findData() {
		$this->data = new ArrayIterator($this->model->find($this->findType, [
			'conditions' => $this->conditions,
			'limit' => $this->limit,
			'offset' => $this->offset
		]));
	}
}