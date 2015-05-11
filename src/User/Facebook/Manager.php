<?php

namespace Demo\User\Facebook;

use Demo\Helper\Session;
use Facebook\FacebookJavaScriptLoginHelper;

class Manager
{
	/**
	 * @var Dao
	 */
	private $dao;

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var FacebookJavaScriptLoginHelper
	 */
	private $loginHelper;

	public function __construct(
		Dao $dao, Session $session, FacebookJavaScriptLoginHelper $loginHelper
	)
	{
		$this->dao         = $dao;
		$this->session     = $session;
		$this->loginHelper = $loginHelper;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			Session::getInstance(),
			new FacebookJavaScriptLoginHelper()
		);
	}

	public function connect()
	{
		$session = $this->loginHelper->getSession();

		var_dump($session);
	}
}
