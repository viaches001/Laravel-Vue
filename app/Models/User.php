<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    public function permissions () {
        return $this->hasMany('App\Models\Permission');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'service_calls' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_date',
    ];

    public function getCreatedDateAttribute () {
        return $this->created_at == null ? null : $this->created_at->format('Y-m-d');
    }

    protected $attributes = [
        'id' => null,
        'created_by_user_id' => null,
        'updated_by_user_id' => null,
        'deleted_by_user_id' => null,
        'customer_id' => null,
        'name' => null,
        'email' => null,
        'phone' => null,
        'username' => null,
        'role' => null,
        'service_calls' => 0,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];
}
