<?php

namespace Demo\User;

use Demo\Helper\Session;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class Manager
{
	/**
	 * @var Dao
	 */
	private $dao;

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var ComputerPasswordGenerator
	 */
	private $passwordGenerator;

	public function __construct(
		Dao $dao, Session $session, ComputerPasswordGenerator $passwordGenerator
	)
	{
		$this->dao               = $dao;
		$this->session           = $session;
		$this->passwordGenerator = $passwordGenerator;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			Session::getInstance(),
			self::buildPasswordGenerator()
		);
	}

	private static function buildPasswordGenerator()
	{
		return (new ComputerPasswordGenerator())
			->setUppercase()
			->setLowercase()
			->setNumbers()
			->setSymbols(false)
			->setLength(20);
	}

	public function register($email, $password)
	{
		return $this->dao->register($email, password_hash($password, PASSWORD_DEFAULT));
	}

	public function login($email, $password)
	{
		$user = $this->getByEmail($email);

		if (!$user || !password_verify($password, $user['password'])) {
			throw new \LogicException('Invalid email or password!');
		}

		$this->setSessionData($user['id'], $user['email']);
	}

	public function setSessionData($userId, $email, $picture = null)
	{
		$this->session->reGenerateId();
		$this->session->set('user', Data::create($userId, $email, $picture));
	}

	public function get()
	{
		return $this->session->get('user');
	}

	public function getByEmail($email)
	{
		return $this->dao->getByEmail($email);
	}

	public function logout()
	{
		$this->session->destroy();
	}

	public function generatePassword()
	{
		return $this->passwordGenerator->generatePassword();
	}
}
