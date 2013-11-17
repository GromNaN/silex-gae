<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use google\appengine\api\users\UserService;

$app = new Silex\Application;
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider, [
    'twig.path' => './views', // Use relative paths for cache keys
    'twig.options' => [
        'cache' => './cache/twig',
    ]
]);

$app->get('/', function () {
    return 'Silex rocks!';
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return $app['twig']->render('hello.twig', [
        'name' => $name,
    ]);
});

$app->get('/hello', function (Request $request) use ($app) {
    $user = UserService::getCurrentUser();

    if (!$user) {
        return $app->redirect(UserService::createLoginURL($request->getUri()));
    }

    return $app['twig']->render('hello.twig', [
        'name' => $user->getNickname(),
    ]);
});

$app->run();
