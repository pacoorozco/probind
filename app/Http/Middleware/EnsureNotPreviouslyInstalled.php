<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class EnsureNotPreviouslyInstalled
{
    public function handle($request, Closure $next)
    {
        if ($this->alreadyInstalled()) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * Determine if app was previously installed checking if file exists.
     *
     * @return bool
     */
    public function alreadyInstalled(): bool
    {
        return Storage::disk('local')
            ->exists('installed');
    }
}
