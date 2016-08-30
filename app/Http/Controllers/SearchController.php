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
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Display the records search form.
     *
     * @return \Illuminate\Http\Response
     *
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
     * @param  Request $request *
     * @param  integer $perPage
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function search(Request $request, $perPage = 15)
    {
        // Create a Record query, this will be constructed depending search input fields.
        $query = Record::query();

        // Get search criteria terms
        $domain = $request->input('domain');
        $name = $request->input('name');
        $type = $request->input('type');
        $data = $request->input('data');

        // Construct $query depending the search input fields.
        if ($name) {
            $query->where('name', 'like', $name);
        }

        if ($type && $type !== 'ANY_TYPE') {
            $query->where('type', $type);
        }

        if ($domain) {
            $query->whereHas('zone', function ($q) use ($domain) {
                $q->where('domain', 'like', $domain);
            });
        }

        if ($data) {
            $query->where('data', 'like', $data);
        }

        // Do the $query and paginate the results.
        $records = $query->paginate($perPage);

        // Modify pagination link in order to have search input fields.
        $pagination = $records->appends([
            'domain' => $domain,
            'name'   => $name,
            'type'   => $type,
            'data'   => $data
        ]);

        return view('search.index')
            ->with('records', $records)
            ->with('searchValidInputTypes', self::getSearchSelectValues())
            ->with('pagination', $pagination)
            ->with('domain', $domain)
            ->with('name', $name)
            ->with('type', $type)
            ->with('data', $data);
    }
}
