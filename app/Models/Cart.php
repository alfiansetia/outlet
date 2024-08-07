<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'                => 'integer',
            'user_id'           => 'integer',
            'branch_menu_id'    => 'integer',
            'qty'               => 'integer',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['branch_menu_id'])) {
            $query->where('branch_menu_id',  $filters['branch_menu_id']);
        }
        if (isset($filters['branch_id'])) {
            $query->whereRelation('branch_menu', 'branch_id', $filters['branch_id']);
        }
        if (isset($filters['user_id'])) {
            $query->where('user_id',  $filters['user_id']);
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch_menu()
    {
        return $this->belongsTo(BranchMenu::class);
    }
}
