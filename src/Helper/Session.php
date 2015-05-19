<?php

namespace Demo\Helper;

class Session
{
	public function start()
	{
		ini_set('session.cookie_httponly', '1');

		session_start();
	}

	public function reGenerateId()
	{
		session_regenerate_id(true);
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		return null;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * @param string $key
	 */
	public function remove($key)
	{
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}

	public function destroy()
	{
		$_SESSION = [];
		setcookie(session_name(), '', time() - 3600, '/', null, null, true);
		session_destroy();
	}
}
