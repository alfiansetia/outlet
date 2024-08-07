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
            'id'            => 'integer',
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
