<?php

namespace App\Http\Controllers;

use App\Models\RecentLogin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecentLoginController extends Controller
{
    public function login(RecentLogin $recent)
    {
        if ($recent->token == request('token')) {
            if($recent->isValidToLogin()){
                Auth::guard()->login($recent->user, true);
                $new_recent = $recent->consumeAndGenerateNewInstance();
                RecentLogin::AddNewRecentLoginToCookie($new_recent);
                request()->session()->regenerate();
                return redirect()->intended(RouteServiceProvider::HOME);
            }

            return redirect()->to("/login?email={$recent->user->email}");
        }

        return back()->with(['error'=>'Recent Login is not valid']);
    }

    public function delete(RecentLogin $recent)
    {
        if ($recent->isValidToShow() && $recent->token == request('token')) {
            $recent->update(['is_deleted'=>true]);
        }

        RecentLogin::removeUserFromCookie($recent->user->id);

        return back()->with(['success','Recent Login is Deleted Successfully']);
    }
}
