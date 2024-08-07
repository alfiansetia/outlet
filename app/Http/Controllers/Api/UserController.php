<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function paginate(Request $request)
    {
        $filters = $request->only(['name', 'email', 'role', 'branch_id', 'order_by_id']);
        $data = User::query()->with(['branch'])->filter($filters)->paginate(intval($request->limit ?? 10))->withQueryString();
        return UserResource::collection($data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'branch'        => 'required|exists:branches,id',
            'name'          => 'required|max:50',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:5',
            'role'          => 'required|in:admin,user',
            'is_active'     => 'required|boolean',
            'phone'         => 'nullable|max:15',
            'address'       => 'nullable|max:200',
            'avatar'        => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $user = User::create([
            'branch_id'     => $request->branch,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'is_active'     => $request->is_active,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'avatar'        => $request->avatar,
        ]);
        return $this->response(new UserResource($user), 'Success Insert Data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'branch'        => 'required|exists:branches,id',
            'name'          => 'required|max:50',
            'email'         => 'required|email|unique:users,id,' . $user->id,
            'password'      => 'nullable|min:5',
            'role'          => 'required|in:admin,user',
            'is_active'     => 'required|boolean',
            'phone'         => 'nullable|max:15',
            'address'       => 'nullable|max:200',
            'avatar'        => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $param = [
            'branch_id'     => $request->branch,
            'name'          => $request->name,
            'email'         => $request->email,
            'role'          => $request->role,
            'is_active'     => $request->is_active,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'avatar'        => $request->avatar,
        ];
        if ($request->filled('password')) {
            $param['password'] = Hash::make($request->password);
        }
        $user->update($param);
        return $this->response(new UserResource($user), 'Success Update Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->response(new UserResource($user), 'Success Delete Data!');
    }
}
