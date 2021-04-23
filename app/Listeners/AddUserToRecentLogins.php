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
