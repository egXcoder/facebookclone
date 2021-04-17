<?php

namespace App\Traits;

use App\Models\RecentLogin;
use Illuminate\Support\Facades\Cookie;

trait RecentLoginInCookie
{
    protected static $COOKIE_KEY = 'recent_logins';

    public static function getRecentLoginsFromCookie()
    {
        return json_decode(Cookie::get(self::$COOKIE_KEY), true) ?? [];
    }
    
    public static function setRecentLoginsToCookie(array $recentLogins)
    {
        Cookie::queue(cookie()->forever(self::$COOKIE_KEY, json_encode($recentLogins)));
    }

    public static function AddNewRecentLoginToCookie(RecentLogin $recent)
    {
        $recent_logins = self::getRecentLoginsFromCookie();
        $recent_logins[$recent->user_id] = $recent->token;
        self::setRecentLoginsToCookie($recent_logins);
    }

    public static function removeUserFromCookie($user_id)
    {
        $recent_logins = self::getRecentLoginsFromCookie();
        unset($recent_logins[$user_id]);
        self::setRecentLoginsToCookie($recent_logins);
    }
}
