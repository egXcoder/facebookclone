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
        if($this->isRecentLoginForUserAlreadyInCookie($event->user)){
            return;
        }

        $recent = RecentLogin::create([
            'user_id'=>$event->user->id,
            'token'=>\Illuminate\Support\Str::random(60),
            'ip'=>  request()->server('REMOTE_ADDR'),
            'fingerprint'=>request('fingerprint'),
            'user_agent'=>request('user_agent'),
        ]);

        $this->updateRecentLoginsCookie($recent);
    }

    protected function isRecentLoginForUserAlreadyInCookie($user)
    {
        return array_key_exists($user->id, RecentLogin::getRecentLoginsFromCookie());
    }

    protected function updateRecentLoginsCookie(RecentLogin $recent)
    {
        $recentLogins = $this->updateRecentLogins(RecentLogin::getRecentLoginsFromCookie(), $recent);
        RecentLogin::setRecentLoginsToCookie($recentLogins);
    }
    
    protected function updateRecentLogins(array $recentLogins, RecentLogin $recent)
    {
        $recentLogins[$recent->user_id] = $recent->token;
        return $recentLogins;
    }
}
