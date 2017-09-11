<?php
App::uses('Component', 'Controller');

/**
 * Class ContainerBuilderComponent
 */
class ContainerBuilderComponent extends Component {

	protected static $_container;

	public function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface {
		if (!static::$_container) {
			$this->_createContainer();
		}

		return static::$_container;
	}

	protected function _createContainer() {
		$container = new \CakePHPUtil\Lib\Container\ContainerBuilder();

		static::$_container = $container->getContainer();
	}
}