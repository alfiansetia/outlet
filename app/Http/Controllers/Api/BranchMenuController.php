<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchMenuResource;
use App\Models\BranchMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchMenuController extends Controller
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
        $filters = $request->only(['menu_id', 'category', 'branch_id', 'order_by_id']);
        $data = BranchMenu::query()->with(['menu', 'branch'])->filter($filters)->paginate(intval($request->limit ?? 10))->withQueryString();
        return BranchMenuResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'branch'        => 'required|exists:branches,id',
            'menu'          => [
                'required',
                'exists:menus,id',
                Rule::unique('branch_menus', 'menu_id')->where(function ($query) use ($request) {
                    return $query->where('branch_id', $request->input('branch'));
                }),
            ],
            'price'         => 'required|integer|gte:0',
            'discount'      => 'required|integer|gte:0',
            'is_favorite'   => 'required|boolean',
            'is_available'  => 'required|boolean',
        ]);
        $branch_menu = BranchMenu::create([
            'branch_id'     => $request->branch,
            'menu_id'       => $request->menu,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'is_favorite'   => $request->is_favorite,
            'is_available'  => $request->is_available,
        ]);
        return $this->response(new BranchMenuResource($branch_menu), 'Success Insert Data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BranchMenu $branch_menu)
    {
        return new BranchMenuResource($branch_menu->load(['menu', 'branch']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BranchMenu $branch_menu)
    {
        $this->validate($request, [
            'branch'        => 'required|exists:branches,id',
            'menu'          =>  [
                'required',
                'exists:menus,id',
                Rule::unique('branch_menus', 'menu_id')->where(function ($query) use ($request) {
                    return $query->where('branch_id', $request->input('branch'));
                })->ignore($branch_menu->id),
            ],
            'price'         => 'required|integer|gte:0',
            'discount'      => 'required|integer|gte:0',
            'is_favorite'   => 'required|boolean',
            'is_available'  => 'required|boolean',
        ]);
        $branch_menu->update([
            'branch_id'     => $request->branch,
            'menu_id'       => $request->menu,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'is_favorite'   => $request->is_favorite,
            'is_available'  => $request->is_available,
        ]);
        return $this->response(new BranchMenuResource($branch_menu), 'Success Update Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchMenu $branch_menu)
    {
        $branch_menu->delete();
        return $this->response(new BranchMenuResource($branch_menu), 'Success Delete Data!');
    }
}
