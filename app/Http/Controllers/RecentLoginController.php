<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class RecentLoginController extends Controller
{
    public function delete()
    {
        $recent_logins = json_decode(Cookie::get('recent_logins'), true);
        $recent_logins = $this->removeAccountFromRecentLogins($recent_logins, request('account_id'));
        Cookie::queue(cookie()->forever('recent_logins', json_encode($recent_logins)));
        return response()->json(['success'=>'Account is deleted successfully']);
    }

    protected function removeAccountFromRecentLogins(array $recent_logins, $id)
    {
        unset($recent_logins[$id]);
        return $recent_logins;
    }
}
