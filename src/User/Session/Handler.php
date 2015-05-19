<?php

namespace Demo\User\Session;

class Handler extends \Demo\Helper\Session
{
	const KEY = 'user';

	public function setData($userId, $email, $picture = null)
	{
		$this->set(self::KEY, Data::create($userId, $email, $picture));
	}

	/**
	 * @return \Demo\User\Session\Data
	 */
	public function getData()
	{
		return $this->get(self::KEY);
	}
}
