<?php

namespace Core;

use PDO;
use PDOException;
use Yosymfony\Toml\Toml;

class VPDO
{
    private static PDO|null $connection = null;
    private static array $dataconfig;

    private function __construct() {}
    private function __clone() {}

    static public function connect(): PDO|false
    {
        if (self::$connection !== null) {
            return self::$connection;
        } else {
            self::$dataconfig = Toml::ParseFile('../config/database.toml');
            foreach (self::$dataconfig["data"] as $database) {
                try {
                    self::$connection = new PDO(sprintf('%s:host=%s;dbname=%s;port=%d;charset=%s', $database["driver"], $database["host"], $database["db"], $database["port"], $database["schema"]), $database["username"], $database["password"]);
                    return self::$connection;
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "\n";
                }
            }
        }
        return false;
    }
}
