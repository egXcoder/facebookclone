<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RecentLogin;
use App\Providers\RouteServiceProvider;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login', [
            'recent_logins' => $this->buildRecentLogins()
        ]);
    }
        
    protected function buildRecentLogins()
    {
        $logins = [];
        foreach (RecentLogin::getRecentLoginsFromCookie() as $user_id=>$token) {
            $recent = RecentLogin::where('user_id', $user_id)->where('token', $token)->first();
            if ($recent && $recent->isValidToShow()) {
                $logins[] = [
                    'id'=>$recent->id,
                    'token'=>$recent->token,
                    'name'=>$recent->user->name,
                    'image_url'=>$recent->user->image_url
                ];
            }
        }
        return $logins;
    }
}
