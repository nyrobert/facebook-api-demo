<?php

require 'vendor/autoload.php';

use Demo\User\Manager as UserManager;
use Demo\Facebook\Manager as FacebookManager;
use Demo\Helper\Request as RequestHelper;
use Demo\Helper\Response as ResponseHelper;

(new \Demo\Helper\Session())->start();

\Facebook\FacebookSession::setDefaultApplication(
	getenv('FACEBOOK_APP_ID'), getenv('FACEBOOK_APP_SECRET')
);

$view = new \Slim\Views\Twig();
$view->parserExtensions = [new \Slim\Views\TwigExtension()];

$app = new \Slim\Slim(['view' => $view]);

$userManager     = UserManager::create();
$facebookManager = FacebookManager::create();

$app->get('/', function () use ($app, $facebookManager) {
	$user = (new \Demo\User\Session\Handler())->getData();

	$vars = [
		'appId'               => getenv('FACEBOOK_APP_ID'),
		'user'                => $user,
		'isFacebookConnected' => $user ? $facebookManager->isConnected($user->id) : false,
	];

	$app->render('index.html.twig', $vars);
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

$app->post('/facebook/login', function () use ($facebookManager) {
	$responseHelper  = ResponseHelper::create();

	try {
		$facebookManager->process();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Facebook\FacebookRequestException $e) {
		$responseHelper->setJsonErrorResponse($e);
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookLogin');

$app->post('/facebook/disconnect', function () use ($facebookManager) {
	$responseHelper  = ResponseHelper::create();

	try {
		$facebookManager->disconnect();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Facebook\FacebookRequestException $e) {
		$responseHelper->setJsonErrorResponse($e);
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookDisconnect');

$app->run();
