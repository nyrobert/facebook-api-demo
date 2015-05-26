<?php

namespace Demo;

use Pimple\Container as PimpleContainer;

class Container
{
	const FACEBOOK_MANAGER = 'facebookManager';

	/**
	 * @param PimpleContainer $container
	 *
	 * @return \Demo\Facebook\Manager
	 */
	public static function getFacebookManager(PimpleContainer $container)
	{
		$container[self::FACEBOOK_MANAGER] = function () {
			return new \Demo\Facebook\Manager(
				\Demo\Facebook\Dao::create(),
				\Demo\User\Manager::create(),
				new \Demo\User\Session\Handler(),
				new \Demo\Facebook\Api(),
				(new \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator())
					->setUppercase()
					->setLowercase()
					->setNumbers()
					->setSymbols(false)
					->setLength(20)
			);
		};

		return $container[self::FACEBOOK_MANAGER];
	}
}
