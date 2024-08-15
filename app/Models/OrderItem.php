<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'                => 'integer',
            'order_id'          => 'integer',
            'branch_menu_id'    => 'integer',
            'price'             => 'integer',
            'discount'          => 'integer',
            'qty'               => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function branch_menu()
    {
        return $this->belongsTo(BranchMenu::class);
    }
}
