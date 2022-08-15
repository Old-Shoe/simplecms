<?php

namespace Core;

use Core\Include\xPDOConstruct;
use Exception;
use PDOException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Core\Include\Phared;
use xPDO\xPDO;

class Extensions
{
    private xPDO $connection;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __construct()
    {
        $pdo = new xPDOConstruct();
        $this->connection = $pdo->get('primary');
        $this->connection->connect();
    }

    /**
     * @throws Exception
     */
    public function install(string|array $phar): void
    {
        $info = null;
        //$alias = null;
        if (is_array($phar))
        {
            foreach ($phar as $value)
            {
                $ext[$value] = new Phared(SIMPLECMS_EXT_DIR. $value);
                $info[$value] = $ext[$value]->getInfo();
            }
        } else {
            $ext = new Phared(SIMPLECMS_EXT_DIR. $phar);
            $info = $ext->getInfo();
            //$alias = $ext->getAlias();
        }

        $sql = 'INSERT INTO extensions (name, file_name, author, version, internal, alias, license) VALUES (:name,:file_name,:author,:version,:internal,:alias,:license)';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $info['name']);
        $stmt->bindValue(':alias', $info['alias']);
        $stmt->bindValue(':file_name', $phar);
        $stmt->bindValue(':author', $info['author']);
        $stmt->bindValue(':version', $info['version']);
        $stmt->bindValue(':internal', $info['internal']);
        $stmt->bindValue(':license', $info['license']);

        try {
            $this->connection->beginTransaction();
            $stmt->execute();
            $this->connection->commit();
        } catch (PDOException $exception) {
            $this->connection->rollback();
            throw $exception;
        }
        $stmt->closeCursor();

    }
    public function uninstall(string $phar): void
    {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare("DELETE FROM extensions WHERE alias=? OR file_name=?");
            $stmt->execute($phar);
        } catch (PDOException $exception) {
            $this->connection->rollback();
            throw $exception;
        }
        $stmt->closeCursor();
    }
    public function load(string $phar): void
    {

    }
}