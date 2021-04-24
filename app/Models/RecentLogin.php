<?php

namespace App\Models;

use App\Traits\RecentLoginInCookie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentLogin extends Model
{
    use HasFactory,RecentLoginInCookie;

    protected $casts= [
        'is_deleted'=>'boolean'
    ];

    public const DAYS_FOR_RECENT_LOGIN_TO_BE_VALID = 10;

    protected $guarded = [];

    public static function createFromRequest($parameters)
    {
        return self::create(
            array_merge([
                'user_id'=>request('user_id'),
                'token'=>\Illuminate\Support\Str::random(60),
                'ip'=>  request()->server('REMOTE_ADDR'),
                'fingerprint'=>request('fingerprint'),
                'user_agent'=>request()->server('HTTP_USER_AGENT'),
            ], $parameters)
        );
    }
    
    public function isValidToLogin()
    {
        return $this->isValidToShow() &&
        $this->checkIfSameFingerprint(request('fingerprint')) &&
        !$this->isCreatedFromLongTime();
    }

    public function isValidToShow()
    {
        return $this->user && !$this->is_deleted;
    }

    protected function checkIfSameFingerprint(String $fingerprint)
    {
        return $this->fingerprint == $fingerprint;
    }

    protected function isCreatedFromLongTime()
    {
        if ($this->created_at->diffInDays(Carbon::now())>self::DAYS_FOR_RECENT_LOGIN_TO_BE_VALID) {
            return true;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
