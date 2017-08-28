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
		$container = new \Symfony\Component\DependencyInjection\ContainerBuilder();
		$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader(
			$container,
			new \Symfony\Component\Config\FileLocator(CONTAINER_CONFIG_PATH)
		);
		$loader->load(CONTAINER_CONFIG_FILE);

		static::$_container = $container;
	}
}