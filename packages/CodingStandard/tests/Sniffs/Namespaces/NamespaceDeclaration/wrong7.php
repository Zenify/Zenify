<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\Adapter\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;

final class SomeController
{

	public function someAction()
	{
		return new Response;
	}

}
