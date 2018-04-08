<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class Locale
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('locale')) {
            session(['locale' => $request->getPreferredLanguage(config('app.locales'))]);
        }
        app()->setLocale(session('locale'));
        $locale = session('locale');
        $conversion = [
            'fr' => 'fr_FR.utf8',
            'en' => 'en_US.utf8',
        ];
        $locale = $conversion[$locale];
        setlocale(LC_ALL, $locale);
        return $next($request);
    }
}