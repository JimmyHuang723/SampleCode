<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
//use Debugbar;
//use Illuminate\Support\Facades\Log;

class Language {

    public function __construct(Application $app, Request $request) {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        Debugbar::debug($request);
        if(isset($_COOKIE['user_locale'])) {
            $locale = $_COOKIE['user_locale'];
        }
        else {

            $locale = config('app.locale');
//            Log::debug('no cookie locale is '.$locale);
        }
        $this->app->setLocale($locale);

        return $next($request);
    }

}