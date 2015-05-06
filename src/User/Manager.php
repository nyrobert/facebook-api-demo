<?php

namespace Demo\User;

use Demo\Helper\Session;

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

	public function __construct(Dao $dao, Session $session)
	{
		$this->dao     = $dao;
		$this->session = $session;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			Session::getInstance()
		);
	}

	public function register($email, $password)
	{
		$this->dao->register($email, password_hash($password, PASSWORD_DEFAULT));
	}

	public function login($email, $password)
	{
		$user = $this->dao->getByEmail($email);

		if (!password_verify($password, $user['password'])) {
			throw new \LogicException('Invalid email or password!');
		}

		$this->session->reGenerateId();
		$this->session->set('user', Data::create($user['id'], $user['email']));
	}

	public function logout()
	{
		$this->session->destroy();
	}

	public function get()
	{
		return $this->session->get('user');
	}
}
