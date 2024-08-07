<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
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
        $filters = $request->only(['name', 'order_by_id']);
        $data = Branch::query()->filter($filters)->paginate(intval($request->limit ?? 10))->withQueryString();
        return BranchResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:50',
            'phone'     => 'required|max:15',
            'address'   => 'required|max:200',
            'logo'      => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $branch = Branch::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'logo'      => $request->logo,
        ]);
        return $this->response(new BranchResource($branch), 'Success Insert Data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return new BranchResource($branch->load('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $this->validate($request, [
            'name'      => 'required|max:50',
            'phone'     => 'required|max:15',
            'address'   => 'required|max:200',
            'logo'      => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $branch->update([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'logo'      => $request->logo,
        ]);
        return $this->response(new BranchResource($branch), 'Success Update Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return $this->response(new BranchResource($branch), 'Success Delete Data!');
    }
}
