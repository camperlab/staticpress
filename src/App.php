<?php

namespace Staticpress;

use Slim\Factory\AppFactory;
use Slim\Views\{Twig, TwigMiddleware};
use Slim\Psr7\{Response, Request};
use Twig\Error\LoaderError;

class App
{
    private $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    /**
     * @return void
     * @throws LoaderError
     */
    public function run(): void
    {
        $twig = Twig::create(__DIR__ . '/../public/', ['cache' => false]);
        $this->app->add(TwigMiddleware::create($this->app, $twig));
        $this->app->get('/', function (Request $request, Response $response)
        {
            $hotFile = __DIR__ . '/../public/hot';

            if (!file_exists($hotFile)) {
                $response->getBody()->write(file_get_contents(__DIR__ . '/../public/index.html'));
                return $response;
            }

            return (Twig::fromRequest($request))->render($response, 'index-hot.html', [
                'address' => file_get_contents($hotFile)
            ]);
        });

        $this->app->get('/api/test', function (Request $request, Response $response) {
            $response->getBody()->write('Hello');
            return $response;
        });

        $this->app->get('/assets/{file}', function (Request $request, Response $response, $args) {
            $filePath = __DIR__ . '/../public/assets/' . $args['file'];

            if (!file_exists($filePath))
                return $response->withStatus(404, 'File Not Found');

            $mimeType = match (pathinfo($filePath, PATHINFO_EXTENSION)) {
                'css' => 'text/css',
                'js' => 'application/javascript',
                default => 'text/html',
            };

            $newResponse = $response->withHeader('Content-Type', $mimeType . '; charset=UTF-8');
            $newResponse->getBody()->write(file_get_contents($filePath));
            return $newResponse;
        });

        $this->app->run();
    }
}