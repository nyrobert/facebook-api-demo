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

	public static function create($id, $email)
	{
		$object        = new self();
		$object->id    = (int) $id;
		$object->email = $email;

		return $object;
	}
}
