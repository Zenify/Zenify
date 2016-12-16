<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineExtensionsTree\DI;

use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;


final class TreeExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setClass('Gedmo\Tree\TreeListener')
			->addSetup('setAnnotationReader', ['@Doctrine\Common\Annotations\Reader'])
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}

}
