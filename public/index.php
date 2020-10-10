<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Chimera\Routing\Application;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Http\Middleware\StreamingRequestMiddleware;
use React\Http\Server;
use React\Socket\Server as SocketServer;
use Throwable;
use const PHP_EOL;
use function assert;

$container = require __DIR__ . '/../config/container.php';
assert($container instanceof ContainerInterface);

$app = $container->get('my-api.http');
assert($app instanceof Application);

$loop   = $container->get(LoopInterface::class);
$logger = $container->get(LoggerInterface::class);

$server = new Server($loop, new StreamingRequestMiddleware(), [$app, 'handle']);

$server->on(
    'error',
    static function (Throwable $e) use ($logger): void {
        $logger->error('{message}', ['message' => $e->getMessage()]);
        $logger->debug((string) $e);
    }
);

$socket = new SocketServer('tcp://0.0.0.0:80', $loop);
$server->listen($socket);

echo 'Listening on http://0.0.0.0:80', PHP_EOL;
echo 'Press Ctrl-C to quit.', PHP_EOL;

$loop->run();
