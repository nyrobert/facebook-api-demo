<?php

namespace Demo\Helper;

class Request
{
	/**
	 * @return Request
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getEmail()
	{
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if (!$email) {
			throw new \LogicException('Invalid email!');
		}
		return $email;
	}

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getPassword()
	{
		$password = filter_input(INPUT_POST, 'password');
		if (!$password) {
			throw new \LogicException('Empty password!');
		}
		return $password;
	}
}
