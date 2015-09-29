<?php

namespace Demo;

use Pimple\Container as PimpleContainer;

class Container
{
	const FACEBOOK_MANAGER   = 'facebookManager';
	const PASSWORD_GENERATOR = 'passwordGenerator';

	/**
	 * @param PimpleContainer $container
	 *
	 * @return PimpleContainer
	 */
	public static function create(PimpleContainer $container)
	{
		$container[self::PASSWORD_GENERATOR] = function () {
			return (new \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator())
				->setUppercase()
				->setLowercase()
				->setNumbers()
				->setSymbols(false)
				->setLength(20);
		};

		$container[self::FACEBOOK_MANAGER] = function ($c) {
			return new \Demo\Facebook\Manager(
				\Demo\Facebook\Dao::create(),
				\Demo\User\Manager::create(),
				new \Demo\User\Session\Handler(),
				new \Demo\Facebook\Api(),
				$c[self::PASSWORD_GENERATOR]
			);
		};

		return $container;
	}

	/**
	 * @param PimpleContainer $container
	 *
	 * @return \Demo\Facebook\Manager
	 */
	public static function getFacebookManager(PimpleContainer $container)
	{
		return $container[self::FACEBOOK_MANAGER];
	}
}
