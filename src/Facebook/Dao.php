<?php

namespace Demo\Facebook;

class Dao extends \Demo\Dao
{
	public function save($userId, \Demo\Facebook\Data $data)
	{
		$insert = $this->queryFactory->newInsert();

		$insert
			->into('user_facebook')
			->cols([
				'user_id'          => $userId,
				'access_token'     => $data->accessToken,
				'facebook_user_id' => $data->userId,
				'profile_picture'  => $data->picture
			])
			->set('updated_at', 'NOW()')
			->onDuplicateKeyUpdate('access_token', 'VALUES(access_token)')
			->onDuplicateKeyUpdate('facebook_user_id', 'VALUES(facebook_user_id)')
			->onDuplicateKeyUpdate('profile_picture', 'VALUES(profile_picture)')
			->onDuplicateKeyUpdate('updated_at', 'NOW()');

		$query = $this->pdo->prepare((string) $insert);
		$query->execute($insert->getBindValues());
	}
}
