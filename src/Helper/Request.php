<?php

namespace Demo\Helper;

class Request
{
	public function getEmail()
	{
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if (!$email) {
			throw new \LogicException('Invalid email!');
		}
		return $email;
	}

	public function getPassword()
	{
		$password = filter_input(INPUT_POST, 'password');
		if (!$password) {
			throw new \LogicException('Empty password!');
		}
		return $password;
	}
}
