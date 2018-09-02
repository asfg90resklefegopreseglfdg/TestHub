<?php

namespace App\Repositories\Eloquent;


use App\Test;

class TestRepository
{


    /**
     * @param string $slug
     * @return Test
     */
    public function getTestForCover(string $slug): Test
    {
        return Test::whereSlug($slug)
            ->withCount(['questions',
                'statistics' => function ($query) {
                    $query->where('test_complete', true);
                }])
            ->firstOrFail(['id', 'name', 'answers_public', 'passing_public', 'description', 'duration', 'slug', 'created_at']);
    }

    /**
     * @param string $slug
     * @return Test
     */
    public function getTestForShowQuestions(string $slug): Test
    {
        return Test::whereSlug($slug)
            ->with('questions:id,test_id,question,type_answer', 'questions.answers:id,question_id,answer')
            ->firstOrFail(['id', 'duration', 'name', 'slug']);
    }

    /**
     * @param string $slug
     * @return Test
     */
    public function getTestForShowAnswers(string $slug): Test
    {
        return Test::whereSlug($slug)
            ->with('questions:id,test_id,question,type_answer', 'questions.answers:id,question_id,answer,correct')
            ->firstOrFail(['id', 'duration', 'name', 'slug']);
    }

    /**
     * @param string $slug
     * @return Test
     */
    public function getTestWithCorrectAnswers(string $slug): Test
    {
        return Test::whereSlug($slug)
            ->with(['questions:id,test_id,points,type_answer', 'questions.answers' => function ($query) {
                $query->where('correct', true)->select(['answer', 'question_id']);
            }])
            ->firstOrFail(['id']);
    }

    /**
     * @param $linkToStat
     * @return Test
     */
    public function getTestForShowStatisticsByReference(string $linkToStat): Test
    {
        return Test::where('link_to_stat', '=', $linkToStat)
            ->firstOrFail();
    }

    /**
     * @param $slug
     * @return Test
     */
    public function getTestBySlug($slug): Test
    {
        return Test::whereSlug($slug)
            ->firstOrFail();
    }


    /**
     * get test with statistics for unregistered user
     *
     * @param string $linkToStat
     * @return Test
     */
    public function getTestByLink(string $linkToStat): Test
    {
        return Test::where('link_to_stat_if_no_reg', '=', $linkToStat)
            ->firstOrFail(['id', 'name', 'duration', 'created_at']);
    }


    /**
     * @param int $id
     * @return Test
     */
    public function getTestById(int $id): Test
    {
        return Test::where('id', '=', $id)
            ->firstOrFail();
    }


    /**
     *  Check exists LinkToStat
     *
     * @param $linkToStatIfNoReg
     * @return bool
     */
    public function checkExistsLinkToStatIfNoReg($linkToStatIfNoReg): bool
    {
        return Test::where('link_to_stat_if_no_reg', '=', $linkToStatIfNoReg)
            ->count();
    }

    /**
     * @param int $testId
     * @return bool
     */
    public function checkTestPassingPublic(int $testId): bool
    {
        return Test::where('id', '=', $testId)
            ->where('passing_public', true)
            ->count();
    }



}