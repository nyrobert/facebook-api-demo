<?php

namespace Demo;

use Pimple\Container as Pimple;

class Container
{
	/**
	 * @var Container
	 */
	private static $instance;

	/**
	 * @var Pimple
	 */
	private $pimple;

	/**
	 * @param Pimple $pimple
	 */
	public function __construct(Pimple $pimple)
	{
		$this->pimple = $pimple;
	}

	/**
	 * @return Container
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self(
				new Pimple()
			);
		}
		return self::$instance;
	}

	/**
	 * @return \Demo\Facebook\Manager
	 */
	public function getFacebookManager()
	{
		$this->pimple['facebookManager'] = function () {
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

		return $this->pimple['facebookManager'];
	}
}
