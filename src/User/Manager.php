<?php

namespace Demo\User;

use Demo\User\Session\Handler as SessionHandler;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator as PasswordGenerator;

class Manager
{
	/**
	 * @var Dao
	 */
	private $dao;

	/**
	 * @var SessionHandler
	 */
	private $session;

	/**
	 * @var PasswordGenerator
	 */
	private $passwordGenerator;

	public function __construct(
		Dao $dao, SessionHandler $session, PasswordGenerator $passwordGenerator
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
			new SessionHandler(),
			self::buildPasswordGenerator()
		);
	}

	private static function buildPasswordGenerator()
	{
		return (new PasswordGenerator())
			->setUppercase()
			->setLowercase()
			->setNumbers()
			->setSymbols(false)
			->setLength(20);
	}

	public function register($email, $password)
	{
		return $this->dao->register(
			$email, password_hash($password, PASSWORD_DEFAULT)
		);
	}

	public function registerWithFacebook($email)
	{
		return $this->dao->register(
			$email, password_hash($this->passwordGenerator->generatePassword(), PASSWORD_DEFAULT)
		);
	}

	public function login($email, $password)
	{
		$user = $this->getByEmail($email);

		if (!$user || !password_verify($password, $user->password)) {
			throw new \LogicException('Invalid email or password!');
		}

		$this->session->reGenerateId();
		$this->session->setData($user->id, $user->email);
	}

	public function loginWithFacebook($userId, $email, $picture)
	{
		$this->session->reGenerateId();
		$this->session->setData($userId, $email, $picture);
	}

	public function logout()
	{
		$this->session->destroy();
	}

	public function getByEmail($email)
	{
		$data = $this->dao->getByEmail($email);
		return $data ? Data::create($this->dao->getByEmail($email)) : null;
	}
}
