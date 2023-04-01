<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$twig = Twig::create(__DIR__, ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));
$app->get('/', function (Request $request, Response $response, $args) {

    if (file_exists(__DIR__ . '/hot')) {
        $hotContent = file_get_contents('hot');
        $view = Twig::fromRequest($request);
        return $view->render($response, 'index-hot.html', [
            'address' => $hotContent
        ]);
    } else {
        $response->getBody()->write(file_get_contents(__DIR__ . '/index.html'));
    }

    return $response;
});


$app->get('/api/test', function(Request $request, Response $response) {
    $response->getBody()->write('Hello');
    return $response;
});

$app->run();