<?php

namespace App\Http\Controllers;

use App\Models\RecentLogin;
use Illuminate\Http\Request;

class RecentLoginController extends Controller
{
    public function delete(RecentLogin $recent)
    {
        if ($recent->isValidToShow() && $recent->token == request('token')) {
            $recent->update(['is_deleted'=>true]);
        }

        RecentLogin::removeUserFromCookie($recent->user->id);

        return back()->with(['success','Recent Login is Deleted Successfully']);
    }
}
