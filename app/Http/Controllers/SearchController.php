<?php
/**
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

use App\Record;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Display the records search form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search.index')
            ->with('searchValidInputTypes', self::getSearchSelectValues());
    }

    /**
     * Returns an array for search select's options of Record types
     *
     * @return array
     */
    protected static function getSearchSelectValues()
    {
        return array_merge(
            ['ANY_TYPE' => trans('record/model.any_type')],
            Record::$validInputTypes
        );
    }

    /**
     * Display the records search results.
     *
     * @param  Request $request
     * @param  integer $perPage
     *
     * @return \Illuminate\View\View
     */
    public function search(Request $request, $perPage = 15)
    {
        // Get search criteria terms
        $searchTerms = [
            'domain' => $request->input('domain'),
            'name'   => $request->input('name'),
            'type'   => $request->input('type'),
            'data'   => $request->input('data')
        ];

        // Do the search query.
        $records = $this->doSearchPaginatedQuery($searchTerms, $perPage);

        return view('search.index')
            ->with('records', $records)
            ->with('searchValidInputTypes', self::getSearchSelectValues())
            ->with('searchTerms', $searchTerms);
    }

    /**
     * Create a query based on provided search terms and return paginated results.
     *
     * @param  array $searchTerms
     * @param  integer $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function doSearchPaginatedQuery($searchTerms, $perPage = 15)
    {
        // Create a Record query, this will be constructed depending search input fields.
        $query = Record::query();

        if ($searchTerms['name']) {
            $query->where('name', 'like', $searchTerms['name']);
        }

        if ($searchTerms['type'] && $searchTerms['type'] !== 'ANY_TYPE') {
            $query->where('type', $searchTerms['type']);
        }

        if ($searchTerms['domain']) {
            $domain = $searchTerms['domain'];
            $query->whereHas('zone', function (Model $q) use ($domain) {
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
