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

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getStatus()
	{
		$status = filter_input(INPUT_POST, 'status');
		if (!$status) {
			throw new \LogicException('Empty status message!');
		}
		return $status;
	}

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getFacebookSignedRequest()
	{
		$signedRequest = filter_input(INPUT_POST, 'signed_request');
		if (!$signedRequest) {
			throw new \LogicException('Empty signed request!');
		}
		return $signedRequest;
	}
}
