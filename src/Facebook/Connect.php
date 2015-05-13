<?php

namespace Demo\Facebook;

use Demo\User\Manager as UserManager;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;

class Connect
{
	/**
	 * @var Dao
	 */
	private $dao;

	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * @var FacebookJavaScriptLoginHelper
	 */
	private $loginHelper;

	public function __construct(
		Dao $dao, UserManager $userManager, FacebookJavaScriptLoginHelper $loginHelper
	)
	{
		$this->dao         = $dao;
		$this->userManager = $userManager;
		$this->loginHelper = $loginHelper;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			UserManager::create(),
			new FacebookJavaScriptLoginHelper()
		);
	}

	public function connect()
	{
		$session = $this->getSession();

		$data = Data::create($session, $this->getProfile($session));

		$user = $this->userManager->getByEmail($data->email);

		if (!$user) {
			$userId = $this->userManager->registerWithFacebook($data->email);
			$email  = $data->email;
		} else {
			$userId = $user->id;
			$email  = $user->email;
		}

		$this->userManager->loginWithFacebook($userId, $email, $data->picture);
	}

	private function getSession()
	{
		$session = $this->loginHelper->getSession();
		$session->validate();

		return $session->getLongLivedSession();
	}

	private function getProfile(FacebookSession $session)
	{
		return (new FacebookRequest(
			$session, 'GET', '/me?fields=id,email,picture{url}'
		))->execute()->getGraphObject(GraphUser::className());
	}
}
