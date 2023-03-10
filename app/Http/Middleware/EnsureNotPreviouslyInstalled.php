<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class EnsureNotPreviouslyInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->alreadyInstalled()) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * Determine if app was previously installed checking if file exists.
     */
    public function alreadyInstalled(): bool
    {
        return Storage::disk('local')
            ->exists('installed');
    }
}
