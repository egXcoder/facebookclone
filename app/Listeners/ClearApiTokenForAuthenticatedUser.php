<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;

class ClearApiTokenForAuthenticatedUser
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
    public function handle(Logout $event)
    {
        $this->clearApiTokenForUser($event->user);
    }

    protected function clearApiTokenForUser(User $user)
    {
        $user->update([
            'api_token'=>null
        ]);

        Cookie::queue(cookie()->forget('api_token')->withHttpOnly(false));
    }
}
