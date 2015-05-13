<?php

require 'vendor/autoload.php';

use Demo\User\Manager as UserManager;
use Demo\User\Facebook\Manager as FacebookManager;
use Demo\Helper\Request as RequestHelper;
use Demo\Helper\Response as ResponseHelper;

\Demo\Helper\Session::getInstance()->start();

\Facebook\FacebookSession::setDefaultApplication(
	getenv('FACEBOOK_APP_ID'), getenv('FACEBOOK_APP_SECRET')
);

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

$app->post('/register', function () use ($userManager) {
	$requestHelper  = new RequestHelper();
	$responseHelper = ResponseHelper::create();

	try {
		$userManager->register($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('register');

$app->post('/login', function () use ($userManager) {
	$requestHelper  = new RequestHelper();
	$responseHelper = ResponseHelper::create();

	try {
		$userManager->login($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('login');

$app->post('/logout', function () use ($userManager) {
	$responseHelper = ResponseHelper::create();

	try {
		$userManager->logout();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('logout');

$app->post('/facebook/connect', function () {
	$responseHelper  = ResponseHelper::create();
	$facebookManager = FacebookManager::create();

	try {
		$facebookManager->connect();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Facebook\FacebookRequestException $e) {
		$responseHelper->setJsonErrorResponse($e);
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookConnect');

$app->run();
