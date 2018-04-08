<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $images = Image::latestWithUser()->paginate(config('app.pagination'));
        return view('home', compact('images'));
    }

    /**
     * @param string $locale
     * @return RedirectResponse
     */
    public function language(string $locale): RedirectResponse
    {
        $locale = \in_array($locale, config('app.locales'), true) ? $locale : config('app.fallback_locale');
        session(['locale' => $locale]);
        return back();
    }
}
