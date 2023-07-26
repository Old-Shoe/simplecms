<?php

declare(strict_types=1);

namespace Core;

class Response
{
    public mixed $fd = 0;

    public mixed $socket;

    public mixed $header;

    public mixed $cookie;

    public mixed $trailer;

    public function __destruct()
    {
    }

    /**
     * @return mixed
     */
    public function initHeader(): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function isWritable(): mixed
    {
        return false;
    }

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function cookie(mixed $name, mixed $value = null, mixed $expires = null, mixed $path = null, mixed $domain = null, mixed $secure = null, mixed $httponly = null, mixed $samesite = null, mixed $priority = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function setCookie(mixed $name, mixed $value = null, mixed $expires = null, mixed $path = null, mixed $domain = null, mixed $secure = null, mixed $httponly = null, mixed $samesite = null, mixed $priority = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function rawcookie(mixed $name, mixed $value = null, mixed $expires = null, mixed $path = null, mixed $domain = null, mixed $secure = null, mixed $httponly = null, mixed $samesite = null, mixed $priority = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $http_code
     * @param mixed|null $reason
     * @return int
     */
    public function status(mixed $http_code, mixed $reason = null): int
    {
        return http_response_code();
    }

    /**
     * @param int $http_code
     * @param mixed|null $reason
     * @return int
     */
    public function setStatusCode(int $http_code, mixed $reason = null): int
    {
        return http_response_code($http_code);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $format
     * @return mixed
     */
    public function header(mixed $key, mixed $value, mixed $format = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $format
     * @return mixed
     */
    public function setHeader(mixed $key, mixed $value, mixed $format = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    public function trailer(mixed $key, mixed $value): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function ping(): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function goaway(): mixed
    {
        return false;
    }

    /**
     * @param mixed $content
     * @return mixed
     */
    public function write(mixed $content): mixed
    {
        return false;
    }

    /**
     * @param mixed|null $content
     * @return mixed
     */
    public function end(mixed $content = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function sendfile(mixed $filename, mixed $offset = null, mixed $length = null): mixed
    {
        return false;
    }

    /**
     * @param mixed $location
     * @param mixed|null $http_code
     * @return mixed
     */
    public function redirect(mixed $location, mixed $http_code = null): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function detach(): mixed
    {
        return false;
    }

    /**
     * @param mixed $server
     * @param mixed|null $fd
     * @return mixed
     */
    public static function create(mixed $server, mixed $fd = null): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function upgrade(): mixed
    {
        return false;
    }

    /**
     * @param mixed $data
     * @param mixed|null $opcode
     * @param mixed|null $flags
     * @return mixed
     */
    public function push(mixed $data, mixed $opcode = null, mixed $flags = null): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function recv(): mixed
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function close(): mixed
    {
        return false;
    }
}