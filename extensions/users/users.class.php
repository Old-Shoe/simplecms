<?php

namespace Users;

class User
{
    public static function add():void
    {

    }

    public static function delete():void
    {

    }

    public static function update():void
    {

    }

    function getRoleAllPermissions($role_id){
        global $conn;
        $sql = "SELECT permissions.* FROM permissions JOIN permission_role ON permissions.id = permission_role.permission_id WHERE permission_role.role_id=?";
        $permissions = getMultipleRecords($sql, 'i', [$role_id]);
        return $permissions;
    }
}