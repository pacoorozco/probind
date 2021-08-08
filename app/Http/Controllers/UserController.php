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

use App\Helpers\Helper;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $users = User::all();

        return view('user.index')
            ->with('users', $users);
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', __('user/messages.create.success'));
    }

    public function show(User $user): View
    {
        return view('user.show')
            ->with('user', $user);
    }

    public function edit(User $user): View
    {
        return view('user.edit')
            ->with('user', $user);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->active,
        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->route('users.index')
            ->with('success', __('user/messages.update.success'));
    }

    public function delete(User $user): View
    {
        return view('user/delete')
            ->with('user', $user);
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Gate::allows('delete-user', $user)) {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', __('user/messages.delete.success'));
        }

        // Trying to delete myself.
        return redirect()->route('users.index')
            ->with('error', __('user/messages.delete.invalid'));
    }

    public function data(DataTables $datatable): JsonResponse
    {
        $users = User::select([
            'id',
            'username',
            'name',
            'email',
            'active',
        ]);

        return $datatable->eloquent($users)
            ->editColumn('username', function (User $user) {
                return Helper::addStatusLabel($user->active, $user->username);
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.actions_dd')
                    ->with('model', 'users')
                    ->with('id', $user->id)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn(['id', 'active'])
            ->toJSON();
    }
}
