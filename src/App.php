<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class StaticPress
{
    private $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    public function run()
    {
        $twig = Twig::create(__DIR__, ['cache' => false]);
        $this->app->add(TwigMiddleware::create($this->app, $twig));
        $this->app->get('/', function (Request $request, Response $response, $args) {

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

    }
}