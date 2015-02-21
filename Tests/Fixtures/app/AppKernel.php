<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel {
	public function registerBundles() {
		return array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
			new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Truelab\KottiModelBundle\TruelabKottiModelBundle(),

			new Truelab\KottiFrontendBundle\TruelabKottiFrontendBundle(),

		);
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
	}

	/**
	 * @return string
	 */
	public function getCacheDir() {
		return sys_get_temp_dir() . '/TruelabKottiFrontendBundle/cache';
	}

	/**
	 * @return string
	 */
	public function getLogDir()
	{
		return sys_get_temp_dir() . '/TruelabKottiFrontendBundle/logs';
	}

}
