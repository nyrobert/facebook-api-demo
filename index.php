<?php

require 'vendor/autoload.php';

(new \Demo\Helper\Session())->start();

\Facebook\FacebookSession::setDefaultApplication(
	getenv('FACEBOOK_APP_ID'), getenv('FACEBOOK_APP_SECRET')
);

$view = new \Slim\Views\Twig();
$view->parserExtensions = [new \Slim\Views\TwigExtension()];

$app = new \Slim\Slim(['view' => $view]);

$container = \Demo\Container::create(new \Pimple\Container());

$facebookManager = \Demo\Container::getFacebookManager($container);
$userManager     = \Demo\User\Manager::create();
$requestHelper   = \Demo\Helper\Request::create();
$responseHelper  = \Demo\Helper\Response::create();

$app->get('/', function () use ($app, $facebookManager) {
	$user = (new \Demo\User\Session\Handler())->getData();

	$vars = [
		'appId'               => getenv('FACEBOOK_APP_ID'),
		'user'                => $user,
		'isFacebookConnected' => $user ? $facebookManager->isConnected($user->id) : false,
	];

	$app->render('index.html.twig', $vars);
})->name('index');

$app->post('/register', function () use ($userManager, $requestHelper, $responseHelper) {
	try {
		$userManager->register($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('register');

$app->post('/login', function () use ($userManager, $requestHelper, $responseHelper) {
	try {
		$userManager->login($requestHelper->getEmail(), $requestHelper->getPassword());

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('login');

$app->post('/logout', function () use ($userManager, $responseHelper) {
	try {
		$userManager->logout();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('logout');

$app->post('/facebook/login', function () use ($facebookManager, $responseHelper) {
	try {
		$facebookManager->process();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Facebook\FacebookRequestException $e) {
		$responseHelper->setJsonErrorResponse($e);
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookLogin');

$app->post('/facebook/disconnect', function () use ($facebookManager, $responseHelper) {
	try {
		$facebookManager->disconnect();

		$responseHelper->setJsonSuccessResponse();
	} catch (\Facebook\FacebookRequestException $e) {
		$responseHelper->setJsonErrorResponse($e);
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('facebookDisconnect');

$app->post('/facebook/uninstall', function () use ($facebookManager, $requestHelper) {
	try {
		$facebookManager->uninstall(
			$requestHelper->getFacebookSignedRequest()
		);
	} catch (\Exception $e) {
		echo 'Error: '.$e->getMessage();
	}
});

$app->post('/status/update', function () use ($responseHelper) {
	try {
		$responseHelper->setJsonSuccessResponse();
	} catch (\Exception $e) {
		$responseHelper->setJsonErrorResponse($e);
	}
})->name('statusUpdate');

$app->run();
