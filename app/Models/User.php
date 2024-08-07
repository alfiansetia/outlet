<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'role',
        'is_active',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'  => 'hashed',
            'is_active' => 'boolean',
            'id'        => 'integer',
            'branch_id' => 'integer',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value && file_exists(public_path('images/avatar/' . $value))) {
                    return asset('images/avatar/' . $value);
                }
                return asset('images/default_avatar.png');
            },
        );
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like',  '%' . $filters['name'] . '%');
        }
        if (isset($filters['email'])) {
            $query->where('email', 'like',  '%' . $filters['email'] . '%');
        }
        if (isset($filters['role'])) {
            $query->where('role',  $filters['role']);
        }
        if (isset($filters['branch_id'])) {
            $query->where('branch_id',  $filters['branch_id']);
        }
        if (isset($filters['order_by_id'])) {
            $query->orderBy('id',  $filters['order_by_id']);
        }
    }
}
