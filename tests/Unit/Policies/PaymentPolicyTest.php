<?php

declare(strict_types=1);

namespace Tipoff\Payments\Tests\Unit\Policies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Payments\Models\Payment;
use Tipoff\Payments\Tests\TestCase;
use Tipoff\Support\Contracts\Models\UserInterface;

class PaymentPolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function view_any()
    {
        $user = self::createPermissionedUser('view payments', true);
        $this->assertFalse($user->can('viewAny', Payment::class));

        $user = self::createPermissionedUser('view payments', false);
        $this->assertFalse($user->can('viewAny', Payment::class));
    }

    /**
     * @test
     * @dataProvider data_provider_for_all_permissions_as_creator
     */
    public function all_permissions_as_creator(string $permission, UserInterface $user, bool $expected)
    {
        $payment = Payment::factory()->make([
            'creator_id' => $user,
        ]);

        $this->assertEquals($expected, $user->can($permission, $payment));
    }

    public function data_provider_for_all_permissions_as_creator()
    {
        return [
            'view-true' => [ 'view', self::createPermissionedUser('view payments', true), true ],
            'view-false' => [ 'view', self::createPermissionedUser('view payments', false), true ],
            'create-true' => [ 'create', self::createPermissionedUser('create payments', true), true ],
            'create-false' => [ 'create', self::createPermissionedUser('create payments', false), true ],
            'update-true' => [ 'update', self::createPermissionedUser('update payments', true), false ],
            'update-false' => [ 'update', self::createPermissionedUser('update payments', false), false ],
            'delete-true' => [ 'delete', self::createPermissionedUser('delete payments', true), false ],
            'delete-false' => [ 'delete', self::createPermissionedUser('delete payments', false), false ],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_for_all_permissions_not_creator
     */
    public function all_permissions_not_creator(string $permission, UserInterface $user, bool $expected)
    {
        $payment = Payment::factory()->make();

        $this->assertEquals($expected, $user->can($permission, $payment));
    }

    public function data_provider_for_all_permissions_not_creator()
    {
        // Permissions are identical for creator or others
        return $this->data_provider_for_all_permissions_as_creator();
    }
}
