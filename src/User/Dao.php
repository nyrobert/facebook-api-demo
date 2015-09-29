<?php

namespace Demo\User;

class Dao extends \Demo\Dao
{
	/**
	 * @param string $email
	 * @param string $password
	 *
	 * @return int
	 */
	public function register($email, $password)
	{
		$insert = $this->queryFactory->newInsert();

		$insert
			->into('user')
			->cols([
				'email'    => $email,
				'password' => $password,
			]);

		$query = $this->pdo->prepare((string) $insert);
		$query->execute($insert->getBindValues());

		return $this->pdo->lastInsertId('id');
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getByEmail($email)
	{
		$select = $this->queryFactory->newSelect();

		$select
			->cols(['*'])
			->from('user')
			->where('email = :email');

		return $this->pdo->fetchOne((string) $select, ['email' => $email]);
	}
}
