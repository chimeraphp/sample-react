<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Http\Middleware\LimitConcurrentRequestsMiddleware;
use React\Http\Middleware\RequestBodyBufferMiddleware;
use React\Http\StreamingServer;
use React\Socket\Server as SocketServer;
use Throwable;
use Zend\Expressive\Application;
use const PHP_EOL;
use function assert;

$container = require __DIR__ . '/../config/container.php';
assert($container instanceof ContainerInterface);

$app = $container->get('my-api.http');
assert($app instanceof Application);

$loop   = $container->get(LoopInterface::class);
$logger = $container->get(LoggerInterface::class);

$server = new StreamingServer(
    [
        new LimitConcurrentRequestsMiddleware(100),
        new RequestBodyBufferMiddleware(10485760),
        [$app, 'handle'],
    ]
);

$server->on(
    'error',
    function (Throwable $e) use ($logger): void {
        $logger->error('{message}', ['message' => $e->getMessage()]);
        $logger->debug((string) $e);
    }
);

$socket = new SocketServer('tcp://0.0.0.0:8080', $loop);
$server->listen($socket);

echo 'Listening on http://0.0.0.0:8080', PHP_EOL;
echo 'Press Ctrl-C to quit.', PHP_EOL;

$loop->run();
