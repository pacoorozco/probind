<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use Torann\Registry\Facades\Registry;

class SettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Registry::all();

        return view('settings.index')
            ->with('settings', $settings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SettingsUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsUpdateRequest $request)
    {
        Registry::store($request->except('_token', '_method'));

        return redirect()->route('settings.index')
            ->with('success', trans('settings/messages.save.success'));
    }
}
