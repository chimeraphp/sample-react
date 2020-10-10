<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Chimera\Routing\Application;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Http\Server;
use React\Socket\Server as SocketServer;
use Throwable;

use function assert;

use const PHP_EOL;
use const SIGINT;

$container = require __DIR__ . '/../config/container.php';
assert($container instanceof ContainerInterface);

$app = $container->get('my-api.http');
assert($app instanceof Application);

$loop   = $container->get(LoopInterface::class);
$logger = $container->get(LoggerInterface::class);

$server = new Server($loop, [$app, 'handle']);

$server->on(
    'error',
    static function (Throwable $e) use ($logger): void {
        $logger->error('{message}' . PHP_EOL, ['message' => $e->getMessage()]);
        $logger->debug($e . PHP_EOL);
    }
);

$socket = new SocketServer('tcp://0.0.0.0:80', $loop);
$server->listen($socket);

$logger->info('Listening on http://0.0.0.0:80' . PHP_EOL);
$logger->info('Press Ctrl-C to quit.' . PHP_EOL);

$loop->addSignal(SIGINT, $handler = static function () use ($logger, $socket, $loop, &$handler): void {
    $logger->info('Shutting down server' . PHP_EOL);

    $socket->close();
    $loop->removeSignal(SIGINT, $handler);
});

$loop->run();
