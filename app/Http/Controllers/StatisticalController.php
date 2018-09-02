<?php

namespace App\Http\Controllers;

use App\Question;
use App\Repositories\Eloquent\StatisticsRepository;
use App\Repositories\Eloquent\TestRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Statistics;
use App\Test;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{

    /**
     *
     * @param Request $request
     * @param StatisticsRepository $statisticsRepository
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfStatisticCreated(Request $request,
                                            StatisticsRepository $statisticsRepository,
                                            User $user)
    {
        $user = $user->getUser();
        $identifier = $request->input('identifier');
        $exists = $statisticsRepository->checkStatisticExists($user, $identifier);

        return response()->json(['exists' => $exists]);
    }

    /**
     * Save new statistic for start test
     *
     * @param $slug
     * @param User $user
     * @param TestRepository $testRepository
     * @return \Illuminate\Http\Response
     */
    public function createAndSaveStatistic($slug,
                                           User $user,
                                           TestRepository $testRepository)
    {
        $user = $user->getUser();
        $test = $testRepository->getTestBySlug($slug);
        $statistics = $user->addStatistic($test);
        $identifier = $statistics->getId();

        return response()->json(['identifier' => $identifier]);
    }


    /**
     * Update statistic after test passed
     *
     * @param  \Illuminate\Http\Request $request
     * @param StatisticsRepository $statisticsRepository
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeTestPassing(Request $request,
                                        StatisticsRepository $statisticsRepository,
                                        User $user)
    {
        $user = $user->getUser();
        $userAnswers = $request->input('userAnswers');
        $identifier = $request->input('identifier');
        $statistics = $statisticsRepository->getStatisticsWithCorrectAnswers($identifier);

        abort_if($user->cant('completeTestPassing', [$statistics]), 500);

        $statistics->completeTestPassing($userAnswers, $statistics->test->questions);

        return response()->json(['redirectTo' => '/stats/' . $statistics->getId()]);
    }

    /**
     * Show statistics list for some test
     *
     * @param $value
     * @param TestRepository $testRepository
     * @param StatisticsRepository $statisticsRepository
     * @return Test
     */
    public function showStatisticsListInTest($value,
                                             TestRepository $testRepository,
                                             StatisticsRepository $statisticsRepository)
    {
        // If user unregistered,
        // elseif registered and all can see test statistics,
        // else statistics can see, only test creator
        if ($testRepository->checkExistsLinkToStatIfNoReg($value)) {
            $test = $testRepository->getTestByLink($value);

        } elseif ($testRepository->checkTestPassingPublic($value)) {
            $test = $testRepository->getTestById($value);

        } else {
            $user = Auth::user();
            $test = $testRepository->getTestById($value);
            abort_if($user->cant('showStatisticsListInTest', [Statistics::class, $test]), 403);
        }
        $statistics = $statisticsRepository->getStatistics($test->id);
        return view('statistics.showStatisticsList', [
            'test' => $test,
            'statistics' => $statistics,
        ]);
    }


    /**
     * Show statistic passed test
     *
     * @param $id
     * @param StatisticsRepository $statisticsRepository
     * @param User $user
     * @return string
     */
    public function showStatistic($id,
                                  StatisticsRepository $statisticsRepository,
                                  User $user)
    {
        $user = $user->getUser();
        $statistic = $statisticsRepository->getStatisticById($id);

        abort_if($user->cant('showStatistic', [$statistic]), 500);
        return view('statistics.showStatistic', [
            'statistic' => $statistic,
            'user' => $user
        ]);
    }

}
