<?php

namespace App\Listeners;

use App\Models\RecentLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class RegenerateRecentLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //if user session has ended, next request will try to re-login via remember_me cookie
        //and that will trigger the login event and this listener will be called
        //since fingerprint and useragent can't be recorded into the recent login, it will throw error
        //so we disabled re-generating recent login when user authenticated via remember
        if (Auth::viaRemember()) {
            return;
        }

        $this->deleteRecentLogins($event->user);

        $recent = RecentLogin::createFromRequest([
            'user_id'=>$event->user->id
        ]);

        RecentLogin::AddNewRecentLoginToCookie($recent);
    }

    protected function deleteRecentLogins($user)
    {
        RecentLogin::where('user_id', $user->id)->update([
            'is_deleted'=>true,
        ]);
    }
}
