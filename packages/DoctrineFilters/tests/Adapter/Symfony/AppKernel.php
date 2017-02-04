<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\Adapter\Symfony;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Zenify\DoctrineFilters\Adapter\Symfony\DoctrineFiltersBundle;


final class AppKernel extends Kernel
{

	public function __construct()
	{
		parent::__construct('Zenify_modular_doctrine_filters' . mt_rand(1, 100), TRUE);
	}


	public function registerBundles() : array
	{
		return [
			new FrameworkBundle,
			new DoctrineBundle,
			new DoctrineFiltersBundle,
		];
	}


	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__ . '/Resources/config/config.yml');
	}

}
