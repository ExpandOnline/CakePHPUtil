<?php

abstract class BaseApiScope {

	/**
	 * @var array
	 */
	protected $_scope_vars = array();

	/**
	 * @param $scopeVar
	 */
	protected function _setScopeVar($scopeVar) {
		$this->_scope_vars[$scopeVar] = true;
	}

	/**
	 * @param $scopeVar
	 *
	 * @return bool
	 */
	protected function _hasScopeVar($scopeVar) {
		return array_key_exists($scopeVar, $this->_scope_vars);
	}

	/**
	 * @return mixed
	 */
	abstract public function getName();

	/**
	 * @return mixed
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$arr = [];
		foreach ($this->_scope_vars as $scopeVar => $value) {
			$arr[] = $this->getName() . '.' . $scopeVar;
		}

		return $arr;
	}

	/**
	 * @return $this
	 */
	public function setCreate() {
		$this->_setScopeVar('create');

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setRead() {
		$this->_setScopeVar('read');

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setUpdate() {
		$this->_setScopeVar('update');

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setDelete() {
		$this->_setScopeVar('delete');

		return $this;
	}

	/**
	 * @return bool
	 */
	public function canCreate() {
		return $this->_hasScopeVar('create');
	}

	/**
	 * @return bool
	 */
	public function canRead() {
		return $this->_hasScopeVar('read');
	}

	/**
	 * @return bool
	 */
	public function canUpdate() {
		return $this->_hasScopeVar('update');
	}

	/**
	 * @return bool
	 */
	public function canDelete() {
		return $this->_hasScopeVar('delete');
	}
}