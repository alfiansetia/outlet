<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
        ];
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('images/menu/' . $value))) {
                    return asset('images/menu/' . $value);
                }
                return asset('images/default_menu.jpg');
            },
        );
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like',  '%' . $filters['name'] . '%');
        }
        if (isset($filters['description'])) {
            $query->where('description', 'like',  '%' . $filters['description'] . '%');
        }
        if (isset($filters['category'])) {
            $query->where('category',  $filters['category']);
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }
}
