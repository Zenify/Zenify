<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\Adapter\Symfony\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Zenify\DoctrineFilters\Contract\FilterInterface;


final class SomeFilter implements FilterInterface
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias) : string
    {
        return $targetTableAlias . '.enabled=0';
    }
}
