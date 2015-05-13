<?php

namespace Demo\User;

class Dao extends \Demo\Dao
{
	public function register($email, $password)
	{
		$insert = $this->queryFactory->newInsert();

		$insert
			->into('user')
			->cols([
				'email'    => $email,
				'password' => $password,
			]);

		$query = $this->pdo->prepare($insert->__toString());
		$query->execute($insert->getBindValues());

		return $this->pdo->lastInsertId('id');
	}

	public function getByEmail($email)
	{
		$select = $this->queryFactory->newSelect();

		$select
			->cols(['*'])
			->from('user')
			->where('email = :email');

		return $this->pdo->fetchOne($select->__toString(), ['email' => $email]);
	}
}
