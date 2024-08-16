<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
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
        $filters = $request->only(['branch_id', 'user_id', 'status', 'number', 'order_by_id']);
        $data = Order::query()->with(['branch', 'user'])->filter($filters)->paginate(intval($request->limit ?? 10))->withQueryString();
        return OrderResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subtotal = 0;
        $user = auth()->user();
        if (!$user->branch_id) {
            return $this->unauthorize('You Dont have branch!');
        }
        $carts = Cart::filter([
            'user_id'   => $user->id,
            'branch_id' => $user->branch_id
        ])->with(['user', 'branch_menu.menu'])->get();

        if (count($carts) < 1) {
            return $this->unauthorize('Empty Cart!');
        }

        $this->validate($request, [
            'name'      => 'required|max:20',
            'payment'   => 'required|in:cash,transfer',
            'bill'      => 'required|integer|gte:0',
            'ppn'       => 'required|integer|gte:0',
        ]);

        foreach ($carts as  $item) {
            $total_discount = 0;
            $price = $item->branch_menu->price;
            $discount = $item->branch_menu->discount;
            if ($discount > 0) {
                $total_discount =  ($item->qty * $price * $discount / 100);
            }
            $subtotal = $subtotal + (($item->qty * $price) - $total_discount);
        }
        $value_ppn = 0;
        $ppn = $request->ppn;
        if ($ppn > 0) {
            $value_ppn = $subtotal * $ppn / 100;
        }
        $grand_total = $subtotal + $value_ppn;
        if ($request->bill < $grand_total) {
            return $this->unauthorize('Payment Not Complete!');
        }

        $order = Order::create([
            'branch_id' => $user->branch_id,
            'user_id'   => $user->id,
            'date'      => date('Y-m-d H:i:s'),
            'number'    => strtoupper(Str::random(10)),
            'status'    => 'done',
            'payment'   => $request->payment,
            'ppn'       => $request->ppn,
            'total'     => $grand_total,
            'bill'      => $request->bill,
            'return'    => $request->bill - $grand_total,
        ]);
        foreach ($carts as  $item) {
            OrderItem::create([
                'branch_menu_id'    => $item->branch_menu_id,
                'order_id'          => $order->id,
                'qty'               => $item->qty,
                'price'             => $item->branch_menu->price,
                'discount'          => $item->branch_menu->discount,
            ]);
            $item->delete();
        }
        return $this->response(new OrderResource($order), 'Success Create Order!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order->load(['user', 'branch', 'items.branch_menu.menu']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Order $order)
    {
        if ($order->status == 'cancel') {
            return $this->unauthorize('Order Already Canceled!');
        }
        $this->validate($request, [
            'reason' => 'required|max:200'
        ]);
        $order->update([
            'status'        => 'cancel',
            'cancel_reason' => $request->reason,
        ]);
        return $this->response(new OrderResource($order), 'Success Cancel Order!');
    }
}
