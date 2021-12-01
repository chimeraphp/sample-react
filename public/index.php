<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Chimera\Routing\Application;
use Psr\Container\ContainerInterface;
use Psr\Log\AbstractLogger;
use React\EventLoop\LoopInterface;
use React\Http\Server;
use React\Socket\Server as SocketServer;
use React\Stream\WritableResourceStream;
use React\Stream\WritableStreamInterface;
use Throwable;

use function assert;
use function sprintf;

use const PHP_EOL;
use const SIGINT;
use const STDOUT;

$container = require __DIR__ . '/../config/container.php';
assert($container instanceof ContainerInterface);

$app = $container->get(Application::class);
assert($app instanceof Application);

$loop   = $container->get(LoopInterface::class);
$logger = new class (new WritableResourceStream(STDOUT, $loop)) extends AbstractLogger {
    public function __construct(private WritableStreamInterface $stream) {}

    public function log($level, $message, array $context = array()): void
    {
        $this->stream->write(sprintf('[%s] %s', $level, $message));
    }
};

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
