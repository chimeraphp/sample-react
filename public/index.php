<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Chimera\Routing\Application;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\AbstractLogger;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use React\Stream\WritableResourceStream;
use React\Stream\WritableStreamInterface;
use Throwable;

use function React\Async\async;
use function assert;
use function sprintf;

use const PHP_EOL;
use const SIGINT;
use const STDOUT;

$container = require __DIR__ . '/../config/container.php';
assert($container instanceof ContainerInterface);

$app = $container->get(Application::class);
assert($app instanceof Application);

$logger = new class (new WritableResourceStream(STDOUT)) extends AbstractLogger {
    public function __construct(private readonly WritableStreamInterface $stream) {}

    public function log($level, $message, array $context = array()): void
    {
        $this->stream->write(sprintf('[%s] %s', $level, $message));
    }
};

$server = new HttpServer(
    async(
        static function (ServerRequestInterface $request) use ($app): ResponseInterface {
            return $app->handle($request);
        }
    )
);

$server->on(
    'error',
    static function (Throwable $e) use ($logger): void {
        $logger->error('{message}' . PHP_EOL, ['message' => $e->getMessage()]);
        $logger->debug($e . PHP_EOL);
    }
);

$socket = new SocketServer('tcp://0.0.0.0:80');
$server->listen($socket);

$logger->info('Listening on http://0.0.0.0:80' . PHP_EOL);
$logger->info('Press Ctrl-C to quit.' . PHP_EOL);

$loop = Loop::get();

$handler = static function () use ($logger, $socket, $loop, $server, &$handler): void {
    $logger->info('Shutting down server' . PHP_EOL);

    $server->removeAllListeners();
    $socket->close();

    $loop->removeSignal(SIGINT, $handler);
    $loop->removeSignal(SIGTERM, $handler);
};

$loop->addSignal(SIGINT, $handler);
$loop->addSignal(SIGTERM, $handler);

$loop->run();
