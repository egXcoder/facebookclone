<?php

namespace App\Traits;

use App\Models\RecentLogin;
use Illuminate\Support\Facades\Cookie;
use stdClass;

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

    public static function buildRecentLoginsFromCookie()
    {
        $logins = [];
        foreach (self::getRecentLoginsFromCookie() as $user_id=>$token) {
            $recent = self::where('user_id', $user_id)->where('token', $token)->first();
            if ($recent && $recent->isValidToShow()) {
                $logins[] = fill_object_from_array(new stdClass,[
                    'id'=>$recent->id,
                    'token'=>$recent->token,
                    'name'=>$recent->user->name,
                    'image_url'=>$recent->user->image_url
                ]);
            }
        }
        return $logins;
    }
}
