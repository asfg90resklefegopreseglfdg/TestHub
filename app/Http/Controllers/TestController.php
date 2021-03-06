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
    protected $testRep;

    public function __construct(TestRepository $testRepository)
    {
        $this->testRep = $testRepository;
    }

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
     * @return Response
     */
    public function store(StoreTest $request,
                          User $user)
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showQuestions($slug)
    {
        $test = $this->testRep->getTestForShowQuestions($slug);
        return view('test.passing', ['test' => $test]);
    }

    /**
     * Display test cover
     *
     * @param $slug
     * @return Response
     * @internal param Test $test
     */
    public function showCover($slug)
    {
        $test = $this->testRep->getTestForCover($slug);
        return view('test.cover', [
            'test' => $test,
        ]);
    }

    /**
     * @param $slug
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publish($slug,
                            User $user)
    {
        $user = $user->getUser();
        $test = $this->testRep->getTestBySlug($slug);
        abort_if($user->cant('publish', $test), 404);
        return view('test.publish', [
            'user' => $user,
            'test' => $test
        ]);
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAnswers($slug)
    {
        $test = $this->testRep->getTestForShowAnswers($slug);

        return view('test.showAnswers', ['test' => $test]);
    }

}
