<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
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
        $filters = $request->only(['name', 'category', 'description', 'order_by_id']);
        $data = Menu::query()->filter($filters)->paginate(intval($request->limit ?? 10))->withQueryString();
        return MenuResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|max:50',
            'category'      => 'required|in:food,drink,other',
            'description'   => 'required|max:200',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $menu = Menu::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'category'      => $request->category,
            'image'         => $request->image,
        ]);
        return $this->response(new MenuResource($menu), 'Success Insert Data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $this->validate($request, [
            'name'          => 'required|max:50',
            'category'      => 'required|in:food,drink,other',
            'description'   => 'required|max:200',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        $menu->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'category'      => $request->category,
            'image'         => $request->image,
        ]);
        return $this->response(new MenuResource($menu), 'Success Update Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return $this->response(new MenuResource($menu), 'Success Delete Data!');
    }
}
