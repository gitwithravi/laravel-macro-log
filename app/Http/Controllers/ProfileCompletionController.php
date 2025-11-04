<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileCompletionController extends Controller
{
    /**
     * Update the user's profile with required information.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'height' => ['required', 'numeric', 'min:0', 'max:300'],
            'open_api_key' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $request->user()->update($validator->validated());

        return redirect()->route('dashboard')->with('success', 'Profile completed successfully!');
    }
}
