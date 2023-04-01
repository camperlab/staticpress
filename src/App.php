<?php
namespace StaticPress;

use Slim\Factory\AppFactory;
use Slim\Views\{Twig, TwigMiddleware};
use Slim\Psr7\{Response, Request};
use Twig\Error\LoaderError;

class App
{
    private \Slim\App $app;

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

        $this->app->run();
    }
}