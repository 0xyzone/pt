<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150'],
            'organization' => ['nullable', 'string', 'max:150'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'message'      => ['nullable', 'string', 'max:1000'],
        ]);

        DemoRequest::create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('demo_success', true);
    }
}
