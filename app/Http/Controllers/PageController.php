<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('pages.index');
    }

    public function notFound(): View
    {
        return response()->view('pages.404', [], 404);
    }
}
