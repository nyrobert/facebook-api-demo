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
	 * @var string
	 */
	public $picture;

	public static function create($id, $email, $picture = null)
	{
		$object          = new self();
		$object->id      = (int) $id;
		$object->email   = $email;
		$object->picture = $picture;

		return $object;
	}
}
