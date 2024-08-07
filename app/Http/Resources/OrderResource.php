<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'            => $this->id,
            'DT_RowId'      => $this->id,
            'date'          => $this->date,
            'number'        => $this->number,
            'payment'       => $this->payment,
            'trx_id'        => $this->trx_id,
            'status'        => $this->status,
            'cancel_reason' => $this->cancel_reason,
            'ppn'           => $this->ppn,
            'total'         => $this->total,
            'bill'          => $this->bill,
            'return'        => $this->return,
            'branch_id'     => $this->branch_id,
            'user_id'       => $this->user_id,
            'branch'        => new BranchResource($this->whenLoaded('branch')),
            'user'          => new UserResource($this->whenLoaded('user')),
            'items'         => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
