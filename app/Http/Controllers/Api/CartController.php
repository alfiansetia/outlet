<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\BranchMenu;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
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
        $user = auth()->user();
        if (!$user->branch_id) {
            return $this->unauthorize('You Dont have branch!');
        }
        $data = Cart::query()->filter([
            'branch_id'     => $user->branch_id,
            'user_id'       => $user->id,
            'order_by_id'   => $request->order_by_id,
        ])->with(['branch_menu.menu', 'branch_menu.branch', 'user'])->paginate(intval($request->limit ?? 10))->withQueryString();
        return CartResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'menu'          => 'required|exists:branch_menus,id',
            'qty'           => 'required|gt:0',
        ]);
        $cart = Cart::filter([
            'user_id'           => auth()->id(),
            'branch_menu_id'    => $request->menu
        ])->first();
        if (!$cart) {
            $cart = Cart::create([
                'user_id'           => auth()->id(),
                'branch_menu_id'    => $request->menu,
                'qty'               => $request->qty,
            ]);
        } else {
            $cart->update([
                'qty' => $cart->qty + $request->qty
            ]);
        }
        return $this->response(new CartResource($cart), 'Success Add To Cart!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        return new CartResource($cart->load(['user', 'branch_menu.menu']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        $this->validate($request, [
            'qty'   => 'required|gt:0',
        ]);
        $cart->update([
            'qty'   => $request->qty,
        ]);
        return $this->response(new CartResource($cart), 'Success Update Cart!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->response(new CartResource($cart), 'Success Delete Cart!');
    }
}
