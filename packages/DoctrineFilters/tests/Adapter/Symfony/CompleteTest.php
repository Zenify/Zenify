<?php

declare(strict_types=1);

namespace Zenify\DoctrineFilters\Tests\Symfony\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Zenify\DoctrineFilters\Tests\Adapter\Symfony\AppKernel;
use Zenify\DoctrineFilters\Tests\Adapter\Symfony\Controller\SomeController;


final class CompleteTest extends TestCase
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var Kernel
	 */
	private $kernel;


	protected function setUp()
	{
		$this->kernel = new AppKernel;
		$this->kernel->boot();

		$this->entityManager = $this->kernel->getContainer()
			->get('doctrine.orm.default_entity_manager');
	}


	public function testEnableFiltersViaSubscriber()
	{
		$request = new Request;
		$request->attributes->set('_controller', SomeController::class . '::someAction');

		$filters = $this->entityManager->getFilters();
		$this->assertCount(0, $filters->getEnabledFilters());

		$this->kernel->handle($request);
		$this->assertCount(1, $filters->getEnabledFilters());
	}

}