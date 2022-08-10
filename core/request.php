<?php
declare(strict_types=1);

namespace Core;


class Request
{
    public $fd = 0;

    public $header;

    public $server;

    public $cookie;

    public $get;

    public $files;

    public $post;

    public $tmpfiles;

    public function __destruct()
    {
    }

    public function __construct()
    {
        if (!function_exists('getallheaders'))
        {
            function getallheaders(): array
            {
                $headers = [];
                foreach ($_SERVER as $name => $value)
                {
                    if (str_starts_with($name, 'HTTP_'))
                    {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }

        $this->cookie = $_COOKIE;
        $this->header = getallheaders();
    }

    /**
     * Get the request content, kind of like function call fopen('php://input').
     *
     * This method has an alias of \Swoole\Http\Request::rawContent().
     *
     * @return string|false Return the request content back; return FALSE when error happens.
     * @see \Swoole\Http\Request::rawContent()
     * @since 4.5.0
     */
    public function getContent(): string|false
    {
    }

    /**
     * Get the request content, kind of like function call fopen('php://input').
     *
     * Alias of method \Swoole\Http\Request::getContent().
     *
     * @return string|false Return the request content back; return FALSE when error happens.
     * @see \Swoole\Http\Request::getContent()
     */
    public function rawContent(): bool|string
    {
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
    }

    /**
     * @param mixed|null $options
     * @return mixed
     */
    public static function create(mixed $options = null): mixed
    {
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function parse(mixed $data): mixed
    {
    }

    /**
     * @return mixed
     */
    public function isCompleted(): mixed
    {
    }

    /**
     * @return mixed
     */
    public function getMethod(): mixed
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}