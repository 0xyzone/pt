<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function postmatch()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.postmatch', compact('activeMatch'));
    }
}
