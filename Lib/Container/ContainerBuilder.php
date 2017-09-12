<?php
namespace CakePHPUtil\Lib\Container;

class ContainerBuilder {

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


		if(!($env = getenv('SYMFONY_ENV', true) ? getenv('SYMFONY_ENV', true) : getenv('SYMFONY_ENV'))) {
			throw new \InvalidArgumentException('Setting the SYMFONY_ENV environment variable (prod, dev) is required.');
		}
		$loader->load('config_' . $env . '.yml');

		static::$_container = $container;
	}
}