<?php

namespace Core\Include;

use PDO;
use PDOException;
use Yosymfony\Toml\Toml;

class VPDO
{
    private static PDO|null $connection = null;
    private static array $dataconfig;

    private function __construct() {}
    private function __clone() {}

    public static function connect(bool $reuse = true): bool
    {
        if (self::$connection !== null && $reuse) {
            return true;
        } else {
            $path = SIMPLECMS_ROOT_DIR . '/core/config/database.toml';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $file = str_replace('/', "\\", $path);
            } else {
                $file = $path;
            }
            self::$dataconfig = Toml::ParseFile($file);
            foreach (self::$dataconfig["data"] as $database) {
                try {
                    self::$connection = new PDO(sprintf('%s:host=%s;dbname=%s;port=%d;charset=%s', $database["driver"], $database["host"], $database["db"], $database["port"], $database["schema"]), $database["username"], $database["password"]);
                    return true;
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "\n";
                }
            }
        }
        return false;
    }
    public static function close():void
    {
        self::$connection = null;
    }
    public static function modifyRecord($sql, $types, $params):bool
    {
        $stmt = self::$connection->prepare($sql);
        $stmt->bindParam($types, ...$params);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }
    public static function getMultipleRecords($sql, $types = null, $params = []): bool|array
    {
        $stmt = self::$connection->prepare($sql);
        if (!empty($params)) { // parameters must exist before you call bind_param() method
            $stmt->bindParam($types, ...$params);
        }
        $stmt->execute();
        $user = $stmt->fetchAll();
        $stmt->closeCursor();
        return $user;
    }
    public static function getSingleRecord($sql, $var, $param, $type) {
        $stmt = self::$connection->prepare($sql);
        $stmt->bindParam($param,$var, $type);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        $stmt->closeCursor();
        return $result;
    }
}
