<?php

namespace Demo\User;

class Data
{
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string password hash
	 */
	public $password;

	public static function create($data)
	{
		$object           = new self();
		$object->id       = (int) $data['id'];
		$object->email    = $data['email'];
		$object->password = $data['password'];

		return $object;
	}
}
