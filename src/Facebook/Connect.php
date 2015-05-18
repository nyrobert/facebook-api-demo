<?php

namespace Demo\Facebook;

use Demo\User\Manager as UserManager;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator as PasswordGenerator;

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

	/**
	 * @var PasswordGenerator
	 */
	private $passwordGenerator;

	public function __construct(
		Dao $dao,
		UserManager $userManager,
		FacebookJavaScriptLoginHelper $loginHelper,
		PasswordGenerator $passwordGenerator
	)
	{
		$this->dao               = $dao;
		$this->userManager       = $userManager;
		$this->loginHelper       = $loginHelper;
		$this->passwordGenerator = $passwordGenerator;
	}

	public static function create()
	{
		return new self(
			Dao::create(),
			UserManager::create(),
			new FacebookJavaScriptLoginHelper(),
			self::buildPasswordGenerator()
		);
	}

	private static function buildPasswordGenerator()
	{
		return (new PasswordGenerator())
			->setUppercase()
			->setLowercase()
			->setNumbers()
			->setSymbols(false)
			->setLength(20);
	}

	public function connect()
	{
		$session = $this->getSession();

		$data = Data::create((string) $session->getAccessToken(), $this->getProfile($session));

		$user = $this->userManager->getByEmail($data->email);

		if (!$user) {
			$userId = $this->userManager->register(
				$data->email, $this->passwordGenerator->generatePassword()
			);
			$email  = $data->email;
		} else {
			$userId = $user->id;
			$email  = $user->email;
		}

		$this->dao->save($userId, $data);

		$this->userManager->loginCallback($userId, $email, $data->picture);
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
