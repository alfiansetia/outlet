<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'user_id'       => 'integer',
        ];
    }

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('images/branch_logo/' . $value))) {
                    return asset('images/branch_logo/' . $value);
                }
                return asset('images/default.png');
            },
        );
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
