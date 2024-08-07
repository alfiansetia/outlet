<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return new UserResource(auth()->user()->load('branch'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|max:50',
            'phone'         => 'nullable|max:15',
            'address'       => 'nullable|max:200',
            'avatar'        => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $user = auth()->user();
        $user->update([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'avatar'        => $request->avatar,
        ]);
        return $this->response(new UserResource($user), 'Success Update Data!');
    }

    public function passwordUpdate(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'current_password'  => [
                'required',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if (!Hash::check($request->current_password, $user->password)) {
                        $fail('Current password is incorrect.');
                    }
                }
            ],
            'new_password'      => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return $this->response(new UserResource($user), 'Password updated successfully!');
    }
}
