<?php

require 'vendor/autoload.php';

use Demo\User\Manager as UserManager;
use Demo\Helper\Request as RequestHelper;
use Demo\Helper\Response as ResponseHelper;
use Demo\Helper\Session;

Session::getInstance()->start();

Dotenv::load(__DIR__);
Dotenv::required([
	'FACEBOOK_APP_ID', 'FACEBOOK_APP_SECRET', 'MYSQL_HOST', 'MYSQL_USERNAME', 'MYSQL_PASSWORD'
]);

$view = new \Slim\Views\Twig();
$view->parserExtensions = [new \Slim\Views\TwigExtension()];

$app         = new \Slim\Slim(['view' => $view]);
$userManager = UserManager::create();

$app->get('/', function () use ($app, $userManager) {
	$app->render(
		'index.html.twig',
		['appId' => getenv('FACEBOOK_APP_ID'), 'user' => $userManager->get()]
	);
})->name('index');

$app->post('/register', function () use ($app, $userManager) {
	$requestHelper  = new RequestHelper();
	$responseHelper = new ResponseHelper($app);

	try {
		$userManager->register($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('register');

$app->post('/sign-in', function () use ($app, $userManager) {
	$requestHelper  = new RequestHelper();
	$responseHelper = new ResponseHelper($app);

	try {
		$userManager->signIn($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('signIn');

$app->post('/sign-out', function () use ($app, $userManager) {
	$responseHelper = new ResponseHelper($app);

	try {
		$userManager->signOut();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('signOut');

$app->run();
