<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    /**
     * Update email
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request, User $user)
    {
        $user = $user->getUser();
        $email =  $request->input('email');
        $update = $user->updateEmail($email);
        return response()->json(['update' => $update]);
    }

}

