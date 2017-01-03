<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Zenify\DoctrineFilters\Adapter\Symfony;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zenify\DoctrineFilters\Adapter\Symfony\DependencyInjection\Compiler\LoadFiltersCompilerPass;
use Zenify\DoctrineFilters\Adapter\Symfony\DependencyInjection\Extension\DoctrineFiltersExtension;

final class DoctrineFiltersBundle extends Bundle
{
    public function getContainerExtension() : DoctrineFiltersExtension
    {
        return new DoctrineFiltersExtension();
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addCompilerPass(new LoadFiltersCompilerPass());
    }
}
