<?php

namespace App\Repositories\Eloquent;


use App\Statistics;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class StatisticsRepository
{

    /**
     *  Check Statistical data for exists
     *
     * @param User $user
     * @param string $identifier
     * @return bool
     */
    public function checkStatisticExists(User $user, string $identifier): bool
    {
        return Statistics::where('user_id', '=', $user->id)
            ->where('id', '=', $identifier)->exists();
    }

    /**
     * @param string $identifier
     * @return Statistics
     */
    public function getStatisticsWithCorrectAnswers(string $identifier): Statistics
    {
        return Statistics::where('id', '=', $identifier)
            ->with(['test.questions:id,test_id,points,type_answer', 'test.questions.answers' => function ($query) {
                $query->where('correct', true)->select(['answer', 'question_id']);
            }])
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return Statistics
     */
    public function getStatisticById(int $id): Statistics
    {
        return Statistics::where('id', '=', $id)
            ->where('test_complete', true)
            ->with('test:name,id,answers_public,passing_public')
            ->firstOrFail();
    }

    /**
     * get statistics by testId
     *
     * @param int $testId
     * @return LengthAwarePaginator
     */
    public function getStatistics(int $testId): LengthAwarePaginator
    {
        return Statistics::where('test_id', '=', $testId)
            ->where('test_complete', true)
            ->sortable()
            ->with('user:id,username')
            ->paginate(3);
    }


}