<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Zenify\DoctrineFilters\Adapter\Symfony\EventListener;

use Zenify\DoctrineFilters\Contract\FilterManagerInterface;


final class EnableFiltersListener
{

	/**
	 * @var FilterManagerInterface
	 */
	private $filterManager;


	public function setFilterManager(FilterManagerInterface $filterManager)
	{
		$this->filterManager = $filterManager;
	}


	public function onKernelRequest()
	{
		$this->filterManager->enableFilters();
	}

}
