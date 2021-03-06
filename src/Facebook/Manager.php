<?php

namespace Demo\Facebook;

use Demo\User\Manager as UserManager;
use Demo\User\Session\Handler as SessionHandler;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator as PasswordGenerator;

/**
 * @see \Test\Demo\Facebook\ManagerTest
 */
class Manager
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
	 * @var SessionHandler
	 */
	private $sessionHandler;

	/**
	 * @var Api
	 */
	private $facebookApi;

	/**
	 * @var PasswordGenerator
	 */
	private $passwordGenerator;

	/**
	 * @param Dao               $dao
	 * @param UserManager       $userManager
	 * @param SessionHandler    $sessionHandler
	 * @param Api               $facebookApi
	 * @param PasswordGenerator $passwordGenerator
	 */
	public function __construct(
		Dao $dao,
		UserManager $userManager,
		SessionHandler $sessionHandler,
		Api $facebookApi,
		PasswordGenerator $passwordGenerator
	) {
		$this->dao               = $dao;
		$this->userManager       = $userManager;
		$this->sessionHandler    = $sessionHandler;
		$this->facebookApi       = $facebookApi;
		$this->passwordGenerator = $passwordGenerator;
	}

	/**
	 * @return int
	 */
	public function process()
	{
		$data = $this->createData();

		$user = $this->getUser($data->email);

		if (!$user) {
			// register and login
			$userId = $this->userManager->register(
				$data->email,
				$this->passwordGenerator->generatePassword()
			);
			$this->userManager->loginCallback($userId, $data->email, $data->picture);
		} elseif (get_class($user) === 'Demo\User\Data') {
			// login
			$userId = $user->id;
			$this->userManager->loginCallback($userId, $user->email, $data->picture);
		} else {
			// connect
			$userId = $user->id;
		}

		$this->dao->save($userId, $data);

		return $userId;
	}

	/**
	 * @return Data
	 */
	private function createData()
	{
		return Data::create(
			$this->facebookApi->getAccessToken(),
			$this->facebookApi->getProfile()
		);
	}

	/**
	 * @param string $email
	 *
	 * @return \Demo\User\Data|\Demo\User\Session\Data|null
	 */
	private function getUser($email)
	{
		$sessionData = $this->sessionHandler->getData();

		if ($sessionData) {
			return $sessionData;
		} else {
			return $this->userManager->getByEmail($email);
		}
	}

	/**
	 * @param int $userId
	 *
	 * @return bool
	 */
	public function isConnected($userId)
	{
		return $this->dao->isConnected($userId);
	}

	public function disconnect()
	{
		$user = $this->sessionHandler->getData();

		if (!$user) {
			throw new \LogicException('User not logged in!');
		}

		$this->facebookApi->revokeLogin();

		$this->dao->delete($user->id);
	}

	/**
	 * @param string $message
	 */
	public function statusUpdate($message)
	{
		$userId = $this->process();

		$data = Data::createWithArray($this->dao->get($userId));

		$this->facebookApi->statusUpdate($data->accessToken, $message);
	}

	/**
	 * @param string $rawSignedRequest
	 */
	public function uninstall($rawSignedRequest)
	{
		$this->dao->deleteWithFacebookUserId(
			Api::getSignedRequest($rawSignedRequest)->getUserId()
		);
	}
}
