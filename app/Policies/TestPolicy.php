<?php

namespace App\Policies;

use App\Statistics;
use App\User;
use App\Test;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TestPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        if ($user) {
            return true;
        }
        return false;
    }



    /**
     * @param User $user
     * @param Test $test
     * @return bool
     */
    public function publish(User $user, Test $test)
    {
        return $user->id === $test->user_id;

    }


}
