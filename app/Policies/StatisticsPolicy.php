<?php

namespace App\Policies;

use App\Test;
use App\User;
use App\Statistics;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticsPolicy
{
    use HandlesAuthorization;

    /**
     *
     * @param  \App\User $user
     * @param  \App\Statistics $statistics
     * @param Test $test
     * @return bool
     */
    public function completeTestPassing(User $user, Statistics $statistics)
    {
        return $user->getId() == $statistics->user_id;

    }

    /**
     * @param User $user
     * @param Test $test
     * @return bool
     */
    public function showStatisticsListInTest(User $user, Test $test)
    {
        return $user->id === $test->user_id;
    }

    /**
     * @param User $user
     * @param Statistics $statistic
     * @return bool
     */
    public function showStatistic(User $user, Statistics $statistic)
    {
        return $user->id === $statistic->user_id;
    }

}
