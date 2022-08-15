<?php

namespace Core;

use Core\Include\xPDOConstruct;
use DOMDocument;
use Exception;
use FilesystemIterator;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOStatement;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use xPDO\xPDO;

class Router
{
    #[NoReturn] private function __construct() {die("Create not allowed!!!!");}
    #[NoReturn] private function __clone() {die("Copy not allowed!!!!");}

    private static function index(): void
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTMLFile(SIMPLECMS_WEBROOT_DIR. 'index.html');
        echo $doc->saveHTML();
    }

    private static function execute(array $call, array $args): mixed
    {
        $pdo = new xPDOConstruct();
        $conn = $pdo->get('primary');
        $conn->connect();

        $class_instance = xPDOConstruct::getSingleRecord($conn, 'SELECT extension FROM instances WHERE function = :function', $call[1], 'function', PDO::PARAM_STR);
        if($class_instance !== null)
        {
            $file_name = xPDOConstruct::getSingleRecord($conn, 'SELECT file_name FROM extensions where alias = :alias', $class_instance, 'alias', PDO::PARAM_STR);
            require SIMPLECMS_EXT_DIR . DIRECTORY_SEPARATOR . $file_name;
            $GLOBALS['simplecms']['extensions'] = $file_name;
        }

        $asa = implode('::', $call);
        var_dump($asa);
        if (!is_callable($asa)) {
            throw new Exception('Function is not callable');
        }
        return call_user_func(implode('::', $call), args: $args);
    }

    private static function retrieve(array $call): void
    {
        /*$directory = new RecursiveDirectoryIterator(__DIR__, FilesystemIterator::FOLLOW_SYMLINKS);
        $filter = new RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) use ($call) {
            // Skip hidden files and directories.
            if (str_starts_with($current->getFilename(), '.')) {
                return false;
            }
            if (!$current->isDir() && $current->getFilename() === 'wanted_dirname';) {
                // Only recurse into intended subdirectories.
                return str_starts_with($current->getFilename(), $file);
            }
        });
        $iterator = new RecursiveIteratorIterator($filter);
        $files = array();
        foreach ($iterator as $info) {
            $files[] = $info->getPathname();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));*/

        $file = SIMPLECMS_WEBROOT_DIR. implode(DIRECTORY_SEPARATOR, $call);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    public static function route(string $request): void
    {
        $filtered_request = filter_var($request, FILTER_SANITIZE_URL);
        if($filtered_request != '/')
        {
            $request_array = parse_url($filtered_request);
            $call = explode('/', trim($request_array['path'], '/'));
            var_dump($call);
            $args = [];
            parse_str($request_array['query'], $args);

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    self::retrieve($call);
                    break;
                case 'POST':
                    self::execute($call, $args);
                    break;
                case 'PUT':
                    break;
                default:
                    die("Fuckoff!");
            }
        } else
        {
            self::index();
        }
    }
}