<?php

namespace Demo\Facebook;

use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\GraphObject;
use Facebook\Entities\SignedRequest;

class Api
{
	/**
	 * @var FacebookSession
	 */
	private static $session;

	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return (string) self::getSession()->getAccessToken();
	}

	/**
	 * @return GraphUser
	 */
	public function getProfile()
	{
		return (new FacebookRequest(
			self::getSession(),
			'GET',
			'/me?fields=id,email,picture{url}'
		))->execute()->getGraphObject(GraphUser::className());
	}

	/**
	 * @param string $accessToken
	 * @param string $message
	 *
	 * @return GraphObject
	 */
	public function statusUpdate($accessToken, $message)
	{
		(new FacebookRequest(
			self::getSessionWithAccessToken($accessToken),
			'POST',
			'/me/feed',
			['message' => $message]
		))->execute()->getGraphObject();
	}

	public function revokeLogin()
	{
		return (new FacebookRequest(
			self::getSession(),
			'DELETE',
			'/me/permissions'
		))->execute();
	}

	/**
	 * @return FacebookSession
	 */
	private static function getSession()
	{
		if (!self::$session) {
			$session = (new FacebookJavaScriptLoginHelper())->getSession();
			$session->getAccessToken()->isValid();

			self::$session = new FacebookSession(
				$session->getAccessToken()->extend(),
				$session->getSignedRequest()
			);
		}
		return self::$session;
	}

	/**
	 * @param string $accessToken long-term access token
	 *
	 * @return FacebookSession
	 */
	private static function getSessionWithAccessToken($accessToken)
	{
		$session = new FacebookSession($accessToken);
		$session->getAccessToken()->isValid();

		return $session;
	}

	/**
	 * @param string $rawSignedRequest
	 *
	 * @return SignedRequest
	 */
	public static function getSignedRequest($rawSignedRequest)
	{
		return new SignedRequest($rawSignedRequest);
	}
}
