<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchMenuResource extends JsonResource
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
            'discount_price'    => $this->discount_price(),
            'discount'          => $this->discount,
            'is_favorite'       => $this->is_favorite,
            'is_available'      => $this->is_available,
            'branch_id'         => $this->branch_id,
            'menu_id'           => $this->menu_id,
            'branch'            => new BranchResource($this->whenLoaded('branch')),
            'menu'              => new MenuResource($this->whenLoaded('menu')),
        ];
    }
}
