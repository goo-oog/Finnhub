<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AppController;
use App\Controllers\NotFoundController;
use App\Repositories\MySQLStockRepository;
use App\Repositories\StockRepository;
use App\Repositories\FinnhubRepository;
use App\Repositories\StockExchangeRepository;
use App\Services\StockTransactionsService;
use App\Services\ViewService;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use League\Container\Container;

$container = new Container();
$container->add(Cache::class, FilesystemCache::class)->addArgument(__DIR__ . '/../storage/cache/');
$container->add(StockExchangeRepository::class, FinnhubRepository::class)->addArgument(Cache::class);
$container->add(StockRepository::class, MySQLStockRepository::class);
$container->add(ViewService::class)->addArguments([StockExchangeRepository::class, StockRepository::class]);
$container->add(StockTransactionsService::class)->addArguments([StockExchangeRepository::class, StockRepository::class]);
$container->add(AppController::class)->addArguments([ViewService::class, StockTransactionsService::class]);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/', [AppController::class, 'showMainPage']);
    $r->post('/', [AppController::class, 'showMainPage']);
    $r->post('/buy', [AppController::class, 'buy']);
    $r->post('/sell', [AppController::class, 'sell']);
    $r->post('/delete', [AppController::class, 'delete']);

});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        (new NotFoundController())->index();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        (new NotFoundController())->index();
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $container->get($class)->$method($vars);
}