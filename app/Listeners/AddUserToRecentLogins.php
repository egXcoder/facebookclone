<?php

namespace App\Listeners;

use App\Models\RecentLogin;
use Illuminate\Auth\Events\Login;

class AddUserToRecentLogins
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
        if ($this->isRecentLoginForUserAlreadyInCookie($event->user)) {
            return;
        }

        $recent = RecentLogin::createFromRequest([
            'user_id'=>$event->user->id
        ]);

        RecentLogin::AddNewRecentLoginToCookie($recent);
    }

    protected function isRecentLoginForUserAlreadyInCookie($user)
    {
        return array_key_exists($user->id, RecentLogin::getRecentLoginsFromCookie());
    }
}
