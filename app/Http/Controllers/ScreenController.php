<?php

namespace App\Http\Controllers;

use App\Models\User;

class ScreenController extends Controller
{
    public function activematch()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();
        return view('screens.activeMatch', compact('activeMatch'));
    }
    
    public function postMatch()
    {
        $user = User::find(request()->route('user_id'));
        $activeMatch = $user->getActiveMatch();

        return view('screens.postMatch', compact('activeMatch'));
    }
}
