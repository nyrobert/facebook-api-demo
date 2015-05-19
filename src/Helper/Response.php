<?php

namespace Demo\Helper;

class Response
{
	/**
	 * @var \Slim\Slim
	 */
	private $app;

	/**
	 * @param \Slim\Slim $app
	 */
	public function __construct(\Slim\Slim $app)
	{
		$this->app = $app;
	}

	/**
	 * @return Response
	 */
	public static function create()
	{
		return new self(
			\Slim\Slim::getInstance()
		);
	}

	public function setJsonSuccessResponse()
	{
		$this->app->response->headers->set('Content-Type', 'application/json');
		$this->app->response->setBody(json_encode(['success' => true]));
	}

	public function setJsonErrorResponse(\Exception $e)
	{
		$this->app->response->headers->set('Content-Type', 'application/json');
		$this->app->response->setBody(json_encode(
			['success' => false, 'errorMessage' => $e->getMessage()]
		));
	}
}
