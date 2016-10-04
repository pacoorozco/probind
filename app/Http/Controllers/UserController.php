<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Gate;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();

        return view('user.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User();
        $user->username = $request->input('username');
        $user->fill($request->all())->save();

        return redirect()->route('users.index')
            ->with('success', trans('user/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     *
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user.show')
            ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('user.edit')
            ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @param  User              $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->fill($request->all())->save();

        return redirect()->route('users.index')
            ->with('success', trans('user/messages.update.success'));
    }

    /**
     * Remove level page.
     *
     * @param User $user
     *
     * @return \Illuminate\View\View
     */
    public function delete(User $user)
    {
        return view('user/delete')
            ->with('user', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if (Gate::allows('delete-user', $user)) {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', trans('user/messages.delete.success'));
        }

        // Trying to delete myself.
        return redirect()->route('users.index')
            ->with('error', trans('user/messages.delete.invalid'));
    }

    /**
     * Show a list of all the users formatted for Datatables.
     *
     * @param Datatables $dataTable
     *
     * @return Datatables JsonResponse
     */
    public function data(Datatables $dataTable)
    {
        $users = User::get([
            'id',
            'username',
            'name',
            'email',
            'active',
        ]);

        return $dataTable::of($users)
            ->editColumn('username', function (User $user) {
                return Helper::addStatusLabel($user->active, $user->username);
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.actions_dd')
                    ->with('model', 'users')
                    ->with('id', $user->id)
                    ->render();
            })
            ->removeColumn('id')
            ->removeColumn('active')
            ->make(true);
    }
}
