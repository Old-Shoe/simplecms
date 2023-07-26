<?php

declare(strict_types=1);

namespace Users;

use Core\Include\xPDOConstruct;
use Core\Log;
use Monolog\Logger;
use PDOException;
use PhpParser\Node\Expr\Cast\Bool_;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReturnTypeWillChange;
use xPDO\xPDO;

class Permission
{
    /**
     * @var mixed|xPDO|null
     */
    private mixed $connection;
    private Logger $log_handler;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $pdo = new xPDOConstruct();
        $this->log_handler = Log::set(__CLASS__);
        $this->connection = $pdo->get('primary');
        $this->connection->connect();
    }

    /**
     * @return array|false
     */
    #[ReturnTypeWillChange]
    public function getAllPermissions(): array|false
    {
        $stmt = $this->connection->prepare("SELECT * FROM permissions");
        $result = null;

        try {
            $this->connection->beginTransaction();
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (PDOException $exception) {
            $this->connection->rollback();
            $this->log_handler->error(sprintf('%s: %c',$exception->getMessage(), $exception->getCode()));
        }
        $stmt->closeCursor();

        return !is_null($result)? $result : false;
    }

    public function getRoleAllPermissions($role_id): bool|array
    {
        $sql = "SELECT permissions.* FROM permissions JOIN permission_role ON permissions.id = permission_role.permission_id WHERE permission_role.role_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $info['name']);

        try {
            $this->connection->beginTransaction();
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (PDOException $exception) {
            $this->connection->rollback();
            throw $exception;
        }
        $stmt->closeCursor();

        return $result;
    }

    public function saveRolePermissions($permission_ids, $role_id): void
    {
        $sql = "DELETE FROM permission_role WHERE role_id=?";
        $result = xPDOConstruct::modifyRecord($sql, 'i', [$role_id]);

        if ($result) {
            foreach ($permission_ids as $id) {
                $sql_2 = "INSERT INTO permission_role SET role_id=?, permission_id=?";
                xPDOConstruct::modifyRecord($sql_2, 'ii', [$role_id, $id]);
            }
        }
    }
}