<?php

namespace Demo\User;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;

class Dao
{
	/**
	 * @var ExtendedPdo
	 */
	private $pdo;

	/**
	 * @var QueryFactory
	 */
	private $queryFactory;

	public function __construct(ExtendedPdo $pdo, QueryFactory $queryFactory)
	{
		$this->pdo          = $pdo;
		$this->queryFactory = $queryFactory;
	}

	public static function create()
	{
		return new self(
			new ExtendedPdo(
				'mysql:host='.getenv('MYSQL_HOST').';dbname=facebook_api_demo',
				getenv('MYSQL_USERNAME'),
				getenv('MYSQL_PASSWORD')
			),
			new QueryFactory('mysql')
		);
	}

	public function register($email, $password)
	{
		$insert = $this->queryFactory->newInsert();

		$insert->into('user')->cols(['email', 'password']);
		$insert->bindValues(['email' => $email, 'password' => $password]);

		$query = $this->pdo->prepare($insert->__toString());
		$query->execute($insert->getBindValues());
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
