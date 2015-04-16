<?php

namespace Demo;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;

abstract class Dao
{
	/**
	 * @var ExtendedPdo
	 */
	protected $pdo;

	/**
	 * @var QueryFactory
	 */
	protected $queryFactory;

	public function __construct(ExtendedPdo $pdo, QueryFactory $queryFactory)
	{
		$this->pdo          = $pdo;
		$this->queryFactory = $queryFactory;
	}

	public static function create()
	{
		return new static(
			new ExtendedPdo(
				'mysql:host='.getenv('MYSQL_HOST').';dbname='.getenv('DB_NAME'),
				getenv('MYSQL_USERNAME'),
				getenv('MYSQL_PASSWORD')
			),
			new QueryFactory('mysql')
		);
	}
}
