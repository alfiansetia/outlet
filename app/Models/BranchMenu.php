<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchMenu extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'branch_id'     => 'integer',
            'menu_id'       => 'integer',
            'price'         => 'integer',
            'discount'      => 'integer',
            'is_favorite'   => 'boolean',
            'is_available'  => 'boolean',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['branch_id'])) {
            $query->where('branch_id',  $filters['branch_id']);
        }
        if (isset($filters['menu_id'])) {
            $query->where('menu_id',  $filters['menu_id']);
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }

    public function discount_price()
    {
        if ($this->discount < 1) {
            return $this->price;
        }
        return $this->price - ($this->price * $this->discount / 100);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
