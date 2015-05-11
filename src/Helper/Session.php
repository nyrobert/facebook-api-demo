<?php

namespace Demo\Helper;

class Session
{
	/**
	 * @var Session
	 */
	private static $instance;

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function start()
	{
		ini_set('session.cookie_httponly', '1');

		session_start();
	}

	public function reGenerateId()
	{
		session_regenerate_id(true);
	}

	public function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		return null;
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

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
