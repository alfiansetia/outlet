<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'        => 'integer',
            'ppn'       => 'integer',
            'total'     => 'integer',
            'bill'      => 'integer',
            'return'    => 'integer',
            'return'    => 'integer',
            'user_id'   => 'integer',
            'branch_id' => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['branch_id'])) {
            $query->where('branch_id',  $filters['branch_id']);
        }
        if (isset($filters['number'])) {
            $query->where('number', 'like', '%' . $filters['number'] . '%');
        }
        if (isset($filters['user_id'])) {
            $query->where('user_id',  $filters['user_id']);
        }
        if (isset($filters['status'])) {
            $query->where('status',  $filters['status']);
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
