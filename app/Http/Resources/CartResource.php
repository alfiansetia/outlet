<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'user_id'           => $this->user_id,
            'branch_menu_id'    => $this->branch_menu_id,
            'qty'               => $this->qty,
            'user'              => new UserResource($this->whenLoaded('user')),
            'branch_menu'       => new BranchMenuResource($this->whenLoaded('branch_menu')),
        ];
    }
}
