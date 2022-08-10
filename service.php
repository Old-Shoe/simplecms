<?php

require_once __DIR__ . '/vendor/autoload.php';

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

Swoole\Coroutine::set([
    'trace_flags' => SWOOLE_TRACE_HTTP2,
    'log_level' => 0,
]);

$key_dir = __DIR__ . '/../ssl/';

$server = new Swoole\Http\Server('127.0.0.1', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP); //SWOOLE_SOCK_TCP | SWOOLE_SSL

$server->set([
    'open_http2_protocol' => 1,
    'enable_static_handler' => TRUE,
    'document_root' => dirname(__DIR__),
    'worker_num' => 4,
    'task_worker_num' => 4,
    'backlog' => 128,
    'user' => 'http',
    'group' => 'http',
    'hook_flags' => SWOOLE_HOOK_ALL,
    'daemonize' => true,
    'pid_file' => '/var/run/swoole/simplecms.pid',
    'static_handler_locations' => ['/web/static'],
    /*'ssl_cert_file' => $key_dir . '/ssl.crt',
    'ssl_key_file' => $key_dir . '/ssl.key',*/
]);

$server->on("Start", function (Server $server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$server->on("Request", function (Request $request, Response $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$server->start();
