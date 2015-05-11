<?php

require 'vendor/autoload.php';

use Demo\User\Manager as UserManager;
use Demo\Helper\Request as RequestHelper;
use Demo\Helper\Response as ResponseHelper;
use Demo\Helper\Session;

Session::getInstance()->start();

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

$app->post('/login', function () use ($app, $userManager) {
	$requestHelper  = new RequestHelper();
	$responseHelper = new ResponseHelper($app);

	try {
		$userManager->login($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('login');

$app->post('/logout', function () use ($app, $userManager) {
	$responseHelper = new ResponseHelper($app);

	try {
		$userManager->logout();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('logout');

$app->post('/facebook/connect', function () use ($app, $userManager) {
	$responseHelper = new ResponseHelper($app);

	try {
		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookConnect');

$app->run();
