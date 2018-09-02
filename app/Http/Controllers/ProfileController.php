<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\TestRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', [
            'user' => $user,
        ]);
    }
}
