<?php

namespace App\Http\Controllers;


use App\Answer;
use App\Http\Requests\StoreTest;
use App\Question;
use App\Repositories\Eloquent\TestRepository;
use App\Statistics;
use App\Tag;
use App\Test;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class TestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        return view('test.showList');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateForm()
    {
        return view('test.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTest $request
     * @param User $user
     * @param Tag $tag
     * @param Question $question
     * @param Answer $answer
     * @return Response
     */
    public function store(StoreTest $request,
                          User $user,
                          Tag $tag,
                          Question $question,
                          Answer $answer)
    {
        $user = $user->getUser();
        $testOptions = $request->input('testOptions');

        $test = $user->addTest($testOptions);

        $urlToRedirect = "/test/publish/" . $test->getSlug();
        return response()->json(['redirectTo' => $urlToRedirect]);
    }

    /**
     * Display test questions
     *
     * @param $slug
     * @param TestRepository $testRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showQuestions($slug, TestRepository $testRepository)
    {
        $test = $testRepository->getTestForShowQuestions($slug);
        return view('test.passing', ['test' => $test]);
    }

    /**
     * Display test cover
     *
     * @param $slug
     * @param TestRepository $testRepository
     * @return Response
     * @internal param Test $test
     */
    public function showCover($slug, TestRepository $testRepository)
    {
        $test = $testRepository->getTestForCover($slug);
        return view('test.cover', [
            'test' => $test,
        ]);
    }

    /**
     * @param $slug
     * @param TestRepository $testRepository
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publish($slug,
                            TestRepository $testRepository,
                            User $user)
    {
        $user = $user->getUser();
        $test = $testRepository->getTestBySlug($slug);
        abort_if($user->cant('publish', $test), 404);
        return view('test.publish', [
            'user' => $user,
            'test' => $test
        ]);
    }

    /**
     * @param $slug
     * @param TestRepository $testRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAnswers($slug, TestRepository $testRepository)
    {
        $test = $testRepository->getTestForShowAnswers($slug);

        return view('test.showAnswers', ['test' => $test]);
    }

}
