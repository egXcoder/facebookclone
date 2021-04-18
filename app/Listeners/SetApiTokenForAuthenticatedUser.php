<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class SetApiTokenForAuthenticatedUser
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
        $this->setApiTokenForUser($event->user);
    }

    protected function setApiTokenForUser(User $user)
    {
        $token = $this->generateUniqueToken();

        $user->update([
            'api_token'=>$token
        ]);

        Cookie::queue(cookie()->forever('api_token', $token)->withHttpOnly(false));
    }
    
    protected function generateUniqueToken()
    {
        $token = Str::random(60);
        
        if (User::where('api_token', $token)->exists()) {
            return $this->generateUniqueToken();
        }

        return $token;
    }
}
