<?php

namespace Demo\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class Api
{
	/**
	 * @param FacebookSession $session
	 *
	 * @return GraphUser
	 */
	public function getProfile(FacebookSession $session)
	{
		return (new FacebookRequest(
			$session, 'GET', '/me?fields=id,email,picture{url}'
		))->execute()->getGraphObject(GraphUser::className());
	}
}
