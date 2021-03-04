<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddPaymentPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view payments',
            'update payments',
        ];

        $this->createPermissions($permissions);
    }
}
