<?php

namespace Demo\User\Session;

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

	/**
	 * @param int    $id
	 * @param string $email
	 * @param string $picture
	 *
	 * @return Data
	 */
	public static function create($id, $email, $picture = null)
	{
		$object          = new self();
		$object->id      = (int) $id;
		$object->email   = $email;
		$object->picture = $picture;

		return $object;
	}
}
