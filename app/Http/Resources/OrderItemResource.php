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
        return [
            'id'                => $this->id,
            'DT_RowId'          => $this->id,
            'price'             => $this->price,
            'qty'               => $this->qty,
            'discount'          => $this->discount,
            'order_id'          => $this->order_id,
            'branch_menu_id'    => $this->branch_menu_id,
            'order'             => new OrderResource($this->whenLoaded('order')),
            'branch_menu'       => new BranchMenuResource($this->whenLoaded('branch_menu')),
        ];
    }
}
