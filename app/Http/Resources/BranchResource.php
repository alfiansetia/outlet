<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'id'        => $this->id,
            'DT_RowId'  => $this->id,
            'name'      => $this->name,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'logo'      => $this->logo,
            'users'     => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
