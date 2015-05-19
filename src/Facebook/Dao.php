<?php

namespace Demo\Facebook;

class Dao extends \Demo\Dao
{
	/**
	 * @param int  $userId
	 * @param Data $data
	 */
	public function save($userId, Data $data)
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

	/**
	 * @param int $userId
	 *
	 * @return bool
	 */
	public function isConnected($userId)
	{
		$select = $this->queryFactory->newSelect();

		$select
			->cols(['1'])
			->from('user_facebook')
			->where('user_id = :userId');

		return (bool) $this->pdo->fetchValue((string) $select, ['userId' => $userId]);
	}

	/**
	 * @param int $userId
	 */
	public function delete($userId)
	{
		$delete = $this->queryFactory->newDelete();

		$delete
			->from('user_facebook')
			->where('user_id = :userId')
			->bindValue('userId', $userId);

		$query = $this->pdo->prepare((string) $delete);
		$query->execute($delete->getBindValues());
	}
}
