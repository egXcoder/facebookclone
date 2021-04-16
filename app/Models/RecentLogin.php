<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class RecentLogin extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public static function getRecentLoginsFromCookie()
    {
        return json_decode(Cookie::get('recent_logins'), true) ?? [];
    }
    
    public static function setRecentLoginsToCookie(array $recentLogins)
    {
        Cookie::queue(cookie()->forever('recent_logins', json_encode($recentLogins)));
    }

    public function isValidForLogin(){
        if(!$this->isValidToShow()){
            return false;
        }

        if (!$this->checkIfSameFingerprint(request('fingerprint'))) {
            return false;
        }

        if ($this->isCreatedFromLongTime()) {
            return false;
        }


        return true;
    }

    public function isValidToShow()
    {
        if ($this->is_deleted) {
            return false;
        }

        if ($this->isConsumed()) {
            return false;
        }

        if(!$this->user){
            return false;
        }

        return true;
    }

    protected function isConsumed()
    {
        return $this->generated_recent_id;
    }

    protected function checkIfSameFingerprint(String $fingerprint)
    {
        return $this->fingerprint == $fingerprint;
    }

    protected function isCreatedFromLongTime()
    {
        if ($this->created_at->diffInDays(time())>10) {
            return true;
        }
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }
}
