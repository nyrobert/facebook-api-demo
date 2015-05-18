<?php

namespace Demo\User;

use Demo\User\Session\Handler as SessionHandler;

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

	public function __construct(Dao $dao, SessionHandler $session)
	{
		$this->dao     = $dao;
		$this->session = $session;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			new SessionHandler()
		);
	}

	public function register($email, $password)
	{
		return $this->dao->register(
			$email, password_hash($password, PASSWORD_DEFAULT)
		);
	}

	public function login($email, $password)
	{
		$user = $this->getByEmail($email);

		if (!$user || !password_verify($password, $user->password)) {
			throw new \LogicException('Invalid email or password!');
		}

		$this->loginCallback($user->id, $user->email);
	}

	public function loginCallback($userId, $email, $picture = null)
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
