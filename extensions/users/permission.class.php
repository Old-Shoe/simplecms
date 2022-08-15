<?php

namespace Users;

use Core\Include\xPDOConstruct;

class Permission
{
    public function __construct()
    {
        xPDOConstruct::generate();
    }

    public function getAllPermissions(): bool|array
    {
        $sql = "SELECT * FROM permissions";
        return xPDOConstruct::getMultipleRecords($sql);
    }

    public function getRoleAllPermissions($role_id): bool|array
    {
        $sql = "SELECT permissions.* FROM permissions JOIN permission_role ON permissions.id = permission_role.permission_id WHERE permission_role.role_id=?";
        return xPDOConstruct::getMultipleRecords($sql, 'i', [$role_id]);
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