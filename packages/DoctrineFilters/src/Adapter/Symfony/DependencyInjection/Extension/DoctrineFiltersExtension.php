<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Zenify\DoctrineFilters\Adapter\Symfony\DependencyInjection\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


final class DoctrineFiltersExtension extends Extension
{

	public function load(array $configs, ContainerBuilder $containerBuilder)
	{
		$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../Resources/config'));
		$loader->load('services.yml');
	}

}
