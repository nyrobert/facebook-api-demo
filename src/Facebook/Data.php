<?php

namespace Demo\Facebook;

use Facebook\GraphUser;

class Data
{
	/**
	 * @var string long-term access token
	 */
	public $accessToken;

	/**
	 * @var string facebook user id
	 */
	public $userId;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string facebook profile picture
	 */
	public $picture;

	/**
	 * @param string    $accessToken
	 * @param GraphUser $profileData
	 *
	 * @return Data
	 */
	public static function create($accessToken, GraphUser $profileData)
	{
		$data = new self();
		$data->accessToken = $accessToken;
		$data->userId      = $profileData->getId();
		$data->email       = $profileData->getEmail();
		$data->picture     = $profileData->getProperty('picture')->getProperty('url');

		return $data;
	}
}
