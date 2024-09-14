<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }



    public function destroy(User $currentUser): bool
    {
        return $currentUser->is_admin;
    }

    public function update(User $currentUser): bool
    {
        return $currentUser->is_admin;
    }
}
