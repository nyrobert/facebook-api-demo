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

	public function get($userId)
	{
		$select = $this->queryFactory->newSelect();

		$select
			->cols([
				'uf.access_token',
				'uf.facebook_user_id',
				'uf.profile_picture',
				'u.email',
			])
			->from('user_facebook uf')
			->join('INNER', 'user u', 'u.id = uf.user_id')
			->where('uf.user_id = :userId');

		return $this->pdo->fetchOne((string) $select, ['userId' => $userId]);
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

	/**
	 * @param int $facebookUserId
	 */
	public function deleteWithFacebookUserId($facebookUserId)
	{
		$delete = $this->queryFactory->newDelete();

		$delete
			->from('user_facebook')
			->where('facebook_user_id = :facebookUserId')
			->bindValue('facebookUserId', $facebookUserId);

		$query = $this->pdo->prepare((string) $delete);
		$query->execute($delete->getBindValues());
	}
}
