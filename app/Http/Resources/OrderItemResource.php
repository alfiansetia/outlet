<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $subtotal = 0;
        $total_discount = 0;
        if ($this->price > 0 && $this->qty > 0) {
            $priceBeforeDiscount = $this->price;
            if ($this->discount > 0 && $this->discount <= 100) {
                $discountPerItem = ($this->price * $this->discount / 100);
                $priceAfterDiscount = $this->price - $discountPerItem;
                $total_discount = $this->qty * $discountPerItem;
            } else {
                $priceAfterDiscount = $this->price;
            }
            $subtotal = $this->qty * $priceAfterDiscount;
        }
        return [
            'id'                => $this->id,
            'DT_RowId'          => $this->id,
            'price'             => $this->price,
            'qty'               => $this->qty,
            'discount'          => $this->discount,
            'order_id'          => $this->order_id,
            'branch_menu_id'    => $this->branch_menu_id,
            'subtotal'          => $subtotal,
            'total_discount'    => $total_discount,
            'order'             => new OrderResource($this->whenLoaded('order')),
            'branch_menu'       => new BranchMenuResource($this->whenLoaded('branch_menu')),
        ];
    }
}
