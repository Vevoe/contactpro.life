<?php

namespace App\Http\Controllers\Auth;


use Auth;
use Socialite;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/contacts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('guest')->except('logout');
        $this->userService = $userService;
    }

    /**
     * Redirect for Social Logins
     * @param  strin $social
     * @return redirect
     */
    public function socialLogin($social)
    {
        return Socialite::driver($social)->redirect();
    }

    /**
     * Handle Authentication of Social Logins
     * @param  string $social
     * @return redirect
     */
    public function handleProviderCallback($social)
    {
        try {
            $socialUser = Socialite::driver($social)->user();

            $authUser = $this->userService->handleSocialLogin($socialUser, $social);
            Auth::login($authUser, true);

            return redirect($this->redirectTo);
        } catch (\Exception $e) {
            $siteName = ucfirst($social);

            return redirect()
                ->route('login')
                ->with('errorMessage', "You must allow {$siteName} access to proceed.");
        }
    }
}
