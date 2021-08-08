<?php
/*
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Http\Controllers;

use App\Enums\ResourceRecordType;
use App\Models\ResourceRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('search.index')
            ->with('searchValidInputTypes', self::getSearchSelectValues());
    }

    protected static function getSearchSelectValues(): array
    {
        return array_merge(
            ['ANY_TYPE' => __('record/model.any_type')],
            ResourceRecordType::asSelectArray()
        );
    }

    public function search(Request $request, int $perPage = 15): View
    {
        // Get search criteria terms
        $searchTerms = [
            'domain' => $request->input('domain'),
            'name'   => $request->input('name'),
            'type'   => $request->input('type'),
            'data'   => $request->input('data'),
        ];

        // Do the search query.
        $records = $this->doSearchPaginatedQuery($searchTerms, $perPage);

        return view('search.index')
            ->with('records', $records)
            ->with('searchValidInputTypes', self::getSearchSelectValues())
            ->with('searchTerms', $searchTerms);
    }

    private function doSearchPaginatedQuery(array $searchTerms, int $perPage = 15): LengthAwarePaginator
    {
        // Create a ResourceRecord query, this will be constructed depending search input fields.
        $query = ResourceRecord::query();

        if ($searchTerms['name']) {
            $query->where('name', 'like', $searchTerms['name']);
        }

        if ($searchTerms['type'] && $searchTerms['type'] !== 'ANY_TYPE') {
            $query->where('type', $searchTerms['type']);
        }

        if ($searchTerms['domain']) {
            $domain = $searchTerms['domain'];
            $query->whereHas('zone', function (Builder $q) use ($domain) {
                $q->where('domain', 'like', $domain);
            });
        }

        if ($searchTerms['data']) {
            $query->where('data', 'like', $searchTerms['data']);
        }

        // Do the $query and paginate the results.
        return $query->paginate($perPage);
    }
}
