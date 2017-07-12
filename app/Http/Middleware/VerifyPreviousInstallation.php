<?php

namespace App\Http\Middleware;

use Closure;

class VerifyPreviousInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->alreadyInstalled()) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * Determine if app is installed checking the exist of a file.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return \Storage::disk('local')->exists('installed');
    }
}
