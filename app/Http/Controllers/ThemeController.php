<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request)
    {
        $current = $request->cookie('theme', 'light');
        $next = $current === 'dark' ? 'light' : 'dark';

        Cookie::queue('theme', $next, 60 * 24 * 365);

        return back()->with('success', "Theme switched to {$next}.");
    }
}
