<?php
namespace App;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Illuminate\Database\Capsule\Manager as Capsule;

class Bootstrap
{
    /** @var Capsule $capsule */
    private $capsule;

    /** @var Middleware $middleware */
    private $middleware;

    /**
     * Bootstrap constructor.
     *
     * @param Capsule $capsule
     * @param Middleware $middleware
     */
    public function __construct(Capsule $capsule, Middleware $middleware)
    {
        $this->capsule = $capsule;
        $this->middleware = $middleware;
    }

    /**
     * Create db connection
     *
     * @return void
     */
    public function connection(): void
    {
        $this->capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'poker_mysql',
            'port' => '3306',
            'database' => 'poker',
            'username' => 'poker',
            'password' => 'poker'

        ]);

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    /**
     * Router function
     * @param $routes
     *
     * @return void
     */
    public function router($routes): void
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route['httpMethod'], $route['uri'], ['class' => $route['class'], 'method' => $route['method'], 'middleware' => $route['middleware']]);
            }
        });
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
            case Dispatcher::NOT_FOUND:
                redirect('/');
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $class = $handler['class'];
                $method = $handler['method'];
                $middleware = $handler['middleware'];

                if($middleware){
                    $this->callMiddleware($middleware);
                }

                call_user_func_array(array(new $class, $method), $vars);
                break;
        }

    }

    /**
     * Call Middleware
     *
     * @param $middleware
     *
     * @return void
     */
    public function callMiddleware($middleware): void
    {
        $this->middleware->$middleware();
    }
}
