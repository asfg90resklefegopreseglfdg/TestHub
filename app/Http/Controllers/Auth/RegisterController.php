<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterUser;
use App\Repositories\Eloquent\UserRepository;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RedirectsUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterUser $request
     * @param User $user
     * @param UserRepository $userRepository
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUser $request, User $user, UserRepository $userRepository)
    {
        $userParams = $request->all();
        $uniqueId = $request->cookie('uniqueId');
        if ($uniqueId and $userRepository->existsUniqueId($uniqueId)) {
            $user = $user->getUser();
            $user->updateAnonymousUser($userParams);
            $user->clearUniqueId();
            $user->clearAllLinkToStatIfNoReg();
        } else {
            $user = $user->createNewUser($userParams);
        }
        event(new Registered($user));


        Auth::guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

}
