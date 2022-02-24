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
                return $user->active
                    ? $user->present()->username
                    : $user->present()->username . ' ' . $user->present()->activeAsBadge();
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.actions_dd')
                    ->with('model', 'users')
                    ->with('id', $user->id)
                    ->render();
            })
            ->rawColumns(['username', 'actions'])
            ->removeColumn(['id', 'active'])
            ->toJSON();
    }
}
