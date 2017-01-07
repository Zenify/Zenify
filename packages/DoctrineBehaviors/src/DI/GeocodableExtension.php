<?php

declare(strict_types=1);

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Geocodable\Geocodable;
use Knp\DoctrineBehaviors\ORM\Geocodable\GeocodableSubscriber;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


final class GeocodableExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'isRecursive' => TRUE,
		'trait' => Geocodable::class,
		'geolocationCallable' => NULL
	];


	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$geolocationCallable = $this->buildDefinitionFromCallable($config['geolocationCallable']);

		$builder->addDefinition($this->prefix('listener'))
			->setClass(GeocodableSubscriber::class, [
				'@' . $this->getClassAnalyzer()->getClass(),
				$config['isRecursive'],
				$config['trait'],
				$geolocationCallable ? '@' . $geolocationCallable->getClass() : $geolocationCallable
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	/**
	 * @throws AssertionException
	 */
	private function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'isRecursive', 'bool');
		Validators::assertField($config, 'trait', 'type');
		Validators::assertField($config, 'geolocationCallable', 'null|type');
	}

}
