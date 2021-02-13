<?php

declare(strict_types=1);

namespace Tipoff\Payments\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Support\Contracts\Models\UserInterface;
use Tipoff\Payments\Models\Payment;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view payments') ? true : false;
    }

    public function view(UserInterface $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('view payments') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return false;
    }

    public function update(UserInterface $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('view payments') ? true : false;
    }

    public function delete(UserInterface $user, Payment $payment): bool
    {
        return false;
    }

    public function restore(UserInterface $user, Payment $payment): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Payment $payment): bool
    {
        return false;
    }
}
